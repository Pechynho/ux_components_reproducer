import {ActionEvent, Controller} from '@hotwired/stimulus';
import {createHTMLElementFromString} from 'app/utils/dom';
import CmsContentBlockController from 'app/controllers/cms-content-block-controller';
import {replaceAll} from "app/utils/strings";
import Sortable from "sortablejs";
import InvalidArgumentException from "app/errors/invalid-argument-exception";
import {useDebounce} from "stimulus-use";

export default class CmsContentController extends Controller
{
    declare private nextItemIndexValue: number;

    declare private templateTarget: HTMLTemplateElement;
    declare private itemsTarget: HTMLElement;
    declare private blockTargets: HTMLElement[];
    declare private blockRowTargets: HTMLElement[];

    declare private isEmptyClass: string;

    private sortable: Sortable | null = null;

    static values = {
        nextItemIndex: {type: Number, default: 0},
    }
    static targets = [
        'template',
        'items',
        'block',
        'blockRow',
    ];
    static classes = [
        'isEmpty',
    ];
    static debounces = [
        'onBlockConnected',
    ];

    public connect(): void {
        this.initSortable();
        useDebounce(this, {wait: 100});
        setTimeout(() => {
            this.setupMoveButtons();
        });
    }

    public disconnect(): void {
        this.destroySortable();
    }

    private initSortable(): void {
        this.sortable = new Sortable(this.itemsTarget, {
            handle: '[data-cms-content-block-sortable-handle]',
            onEnd: () => this.onBlocksOrderChange(),
        });
    }

    private destroySortable(): void {
        if (this.sortable === null) {
            return;
        }
        this.sortable.destroy();
        this.sortable = null;
    }

    private onBlocksOrderChange(): void {
        this.reindexBlocksOrder();
        this.setupMoveButtons();
        this.blockTargets.length === 0
            ? this.element.classList.add(this.isEmptyClass)
            : this.element.classList.remove(this.isEmptyClass);
    }

    private reindexBlocksOrder(): void {
        for (let i = 0; i < this.blockTargets.length; i++) {
            const block = this.getBlock(i);
            block.setOrder(i);
        }
    }

    private setupMoveButtons(): void {
        for (let i = 0; i < this.blockTargets.length; i++) {
            const block = this.getBlock(i);
            i === 0 ? block.disableMoveUpButton() : block.enableMoveUpButton();
            i === this.blockTargets.length - 1 ? block.disableMoveDownButton() : block.enableMoveDownButton();
        }
    }

    public createItem(event: ActionEvent): void {
        const type = event.params.type;
        const html = replaceAll(this.templateTarget.innerHTML, {
            '__name__': this.nextItemIndexValue.toString(),
        })
        this.nextItemIndexValue++;
        const element = createHTMLElementFromString(html);
        this.itemsTarget.appendChild(element);
        setTimeout(() => {
            const lastBlock = this.getLastBlock();
            lastBlock.maximizeImmediately();
            lastBlock.setBlockType(type);
            this.onBlocksOrderChange();
            this.dispatch('block-created');
        });
    }

    private getBlock(index: number): CmsContentBlockController {
        if (index < 0 || index >= this.blockTargets.length) {
            throw new InvalidArgumentException(`Block with index ${index} does not exist`);
        }
        const element = this.blockTargets[index];
        return this.application.getControllerForElementAndIdentifier(element, 'cms-content-block') as CmsContentBlockController;
    }

    private getBlocks(): CmsContentBlockController[] {
        return this.blockTargets.map((element) => {
            return this.application.getControllerForElementAndIdentifier(element, 'cms-content-block') as CmsContentBlockController;
        });
    }

    private getLastBlock(): CmsContentBlockController {
        return this.getBlock(this.blockTargets.length - 1);
    }

    public moveBlockUp(event: CustomEvent<{ order: number }>): void {
        const order = event.detail.order;
        if (order <= 0 || order >= this.blockRowTargets.length) {
            return;
        }
        const blockRow = this.blockRowTargets[order];
        const prevBlockRow = this.blockRowTargets[order - 1];
        this.itemsTarget.insertBefore(blockRow, prevBlockRow);
        this.onBlocksOrderChange();
    }

    public moveBlockDown(event: CustomEvent<{ order: number }>): void {
        const order = event.detail.order;
        if (order < 0 || order >= this.blockRowTargets.length - 1) {
            return;
        }
        const blockRow = this.blockRowTargets[order];
        const nextBlockRow = this.blockRowTargets[order + 1];
        this.itemsTarget.insertBefore(blockRow, nextBlockRow.nextSibling);
        this.onBlocksOrderChange();
    }

    public removeBlock(event: CustomEvent<{ order: number }>): void {
        const order = event.detail.order;
        if (order < 0 || order >= this.blockRowTargets.length) {
            return;
        }
        const blockRow = this.blockRowTargets[order];
        blockRow.remove();
        this.onBlocksOrderChange();
    }

    public async minimizeAll(): Promise<void> {
        const promises = this.getBlocks().map((block) => block.minimize());
        await Promise.all(promises);
    }

    public async maximizeAll(): Promise<void> {
        const promises = this.getBlocks().map((block) => block.maximize());
        await Promise.all(promises);
    }

    public onBlockConnected(): void {
        this.setupMoveButtons();
    }
}
