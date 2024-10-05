import {ActionEvent, Controller} from '@hotwired/stimulus';
import {
    computePosition,
    flip,
    shift,
    offset,
    arrow,
} from '@floating-ui/dom';
import {createHTMLElementFromTemplate} from 'app/utils/dom';
import {DEBOUNCE_DELAY} from 'app/config/variables';
import {useThrottle} from 'stimulus-use';
import {appendToRoot, Root} from 'app/utils/element-placer';

export default class PopoverController extends Controller<HTMLElement>
{
    private removeOnHideValue!: boolean;
    private hasRemoveOnHideValue!: boolean;
    private rootValue!: Root;
    private hasRootValue!: boolean;
    private dropdownBehaviourValue!: boolean;
    private hasDropdownBehaviourValue!: boolean;
    private placementValue!: Placement;
    private hasPlacementValue!: boolean;
    private templateTarget!: HTMLTemplateElement;
    private caretTopClass!: string;
    private caretBottomClass!: string;
    private isOpenClass!: string;
    private dataElement: HTMLElement | null = null;
    private isPopoverFocused: boolean = false;
    private isPopoverVisible: boolean = false;
    private hideTimeout: NodeJS.Timeout | null = null;

    static values = {
        data: String,
        removeOnHide: Boolean,
        root: {type: String, default: 'body'},
        dropdownBehaviour: Boolean,
        placement: String,
    };
    static classes = [
        'caretTop',
        'caretBottom',
        'isOpen',
    ];
    static targets = [
        'template',
    ];
    static throttles = [
        'computePosition',
    ];

    public connect() {
        useThrottle(this, {wait: 500});
    }

    public disconnect() {
        if (this.dataElement !== null) {
            this.dataElement.remove();
            this.dataElement = null;
        }
        this.clearHideTimeout();
    }

    public async toggle(event: ActionEvent | null = null): Promise<void> {
        if (!this.hasDropdownBehaviourValue || !this.dropdownBehaviourValue) {
            return;
        }
        const target = event?.target;
        if (
            this.hasDropdownBehaviourValue
            && this.dropdownBehaviourValue
            && this.hasRootValue
            && this.rootValue === 'toggler'
            && this.dataElement !== null
            && target instanceof HTMLElement
            && (target === this.dataElement || this.dataElement.contains(target))
        ) {
            return;
        }
        this.isPopoverVisible ? await this.hide() : await this.show();
    }

    public async show(): Promise<void> {
        if (this.isPopoverVisible) {
            return;
        }
        const popover = this.getDataElement();
        popover.style.display = 'block';
        this.isPopoverVisible = true;
        this.element.classList.add(this.isOpenClass);
        popover.classList.add(this.isOpenClass);
        await this.computePosition();
    }

    public hide(): Promise<void> {
        return new Promise((resolve) => {
            if (!this.isPopoverVisible) {
                resolve();
                return;
            }
            if (this.hasDropdownBehaviourValue && this.dropdownBehaviourValue) {
                this.clearHideTimeout();
                this.doHide();
                resolve();
                return;
            }
            this.clearHideTimeout();
            this.hideTimeout = setTimeout(() => {
                if (!this.isPopoverFocused) {
                    this.doHide();
                    resolve();
                }
            }, DEBOUNCE_DELAY);
        });
    }

    private doHide(): void {
        if (!this.isPopoverVisible) {
            return;
        }
        const popover = this.getDataElement();
        popover.style.display = 'none';
        this.isPopoverVisible = false;
        this.isPopoverFocused = false;
        this.element.classList.remove(this.isOpenClass);
        popover.classList.remove(this.isOpenClass);
        if (this.hasRemoveOnHideValue && this.removeOnHideValue) {
            popover.remove();
            this.dataElement = null;
        }
    }

    private clearHideTimeout(): void {
        if (this.hideTimeout !== null) {
            clearTimeout(this.hideTimeout);
            this.hideTimeout = null;
        }
    }

    public onGlobalClick(event: Event): void {
        if (!this.isPopoverVisible) {
            return;
        }
        if (!this.hasDropdownBehaviourValue || !this.dropdownBehaviourValue) {
            return;
        }
        const target = event.target as HTMLElement;
        if (target === this.element || this.element.contains(target)) {
            return;
        }
        if (this.dataElement !== null && (target === this.dataElement || this.dataElement.contains(target))) {
            return;
        }
        this.doHide();
    }

    private getDataElement(): HTMLElement {
        if (this.dataElement !== null) {
            return this.dataElement;
        }
        const element = createHTMLElementFromTemplate(this.templateTarget);
        element.style.display = 'none';
        element.addEventListener('mouseenter', () => this.isPopoverFocused = true);
        element.addEventListener('focus', () => this.isPopoverFocused = true);
        element.addEventListener('mouseleave', async () => {
            this.isPopoverFocused = false;
            if (!this.hasDropdownBehaviourValue || !this.dropdownBehaviourValue) {
                await this.hide();
            }
        });
        element.addEventListener('blur', async () => {
            this.isPopoverFocused = false;
            if (!this.hasDropdownBehaviourValue || !this.dropdownBehaviourValue) {
                await this.hide();
            }
        });
        appendToRoot(this.hasRootValue ? this.rootValue : 'body', element, this.element);
        element.addEventListener('click', (event) => {
            const target = event.target;
            if (target instanceof HTMLElement && (target.hasAttribute('data-popover-close-on-click') || target.closest('[data-popover-close-on-click]') !== null)) {
                setTimeout(() => this.doHide());
            }
        });
        this.dataElement = element;
        return element;
    }

    public async computePosition(): Promise<void> {
        if (!this.isPopoverVisible) {
            return;
        }
        const popover = this.getDataElement();
        const middlewares = [offset(), flip(), shift({padding: 5})];
        const arrowElement = popover.querySelector<HTMLElement>('[data-poppover-caret]');
        if (arrowElement !== null) {
            middlewares.push(arrow({element: arrowElement}));
        }
        const position = await computePosition(this.element, popover, {
            placement: this.hasPlacementValue ? this.placementValue : 'top',
            middleware: middlewares,
        });
        popover.style.left = `${position.x}px`;
        popover.style.top = `${position.y}px`;
        if (arrowElement === null) {
            return;
        }
        const arrowPosition = position.middlewareData.arrow;
        const offsetMiddlewareData = position.middlewareData.offset;
        if (typeof arrowPosition === 'undefined' || typeof offsetMiddlewareData === 'undefined') {
            return;
        }
        if (offsetMiddlewareData.placement.includes('top')) {
            popover.classList.add(this.caretBottomClass);
            popover.classList.remove(this.caretTopClass);
        } else if (offsetMiddlewareData.placement.includes('bottom')) {
            popover.classList.add(this.caretTopClass);
            popover.classList.remove(this.caretBottomClass);
        }
        if (typeof arrowPosition.x === 'number') {
            arrowElement.style.left = `${arrowPosition.x}px`;
        }
        if (typeof arrowPosition.y === 'number') {
            arrowElement.style.top = `${arrowPosition.y}px`;
        }
    }
}

type Placement = 'top' | 'bottom';
