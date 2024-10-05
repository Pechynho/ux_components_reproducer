import {Controller} from '@hotwired/stimulus';
import {SLIDE_DURATION} from "app/config/variables";
import InvalidStateException from "app/errors/invalid-state-exception";

export default class CmsContentBlockController extends Controller
{
    declare private orderTarget: HTMLInputElement;
    declare private typeTarget: HTMLSelectElement;
    declare private typeRowTarget: HTMLElement;
    declare private textRowTarget: HTMLElement;
    declare private embeddedVideoUrlRowTarget: HTMLElement;
    declare private fileRowTarget: HTMLElement;
    declare private filesRowTarget: HTMLElement;
    declare private cardBodyTarget: HTMLElement;
    declare private maximizeButtonTarget: HTMLElement;
    declare private minimizeButtonTarget: HTMLElement;
    declare private moveUpButtonTarget: HTMLElement;
    declare private hasMoveUpButtonTarget: boolean;
    declare private moveDownButtonTarget: HTMLElement;
    declare private hasMoveDownButtonTarget: boolean;
    declare private titleTarget: HTMLElement;

    declare private isAnimatingValue: boolean;
    declare private idValue: string;

    declare private maximizedClass: string;
    declare private minimizedClass: string;

    private isMoveUpButtonDisabled: boolean = false;
    private isMoveDownButtonDisabled: boolean = false;

    static targets = [
        'order',
        'type',
        'typeRow',
        'textRow',
        'embeddedVideoUrlRow',
        'fileRow',
        'filesRow',
        'cardBody',
        'maximizeButton',
        'minimizeButton',
        'moveUpButton',
        'moveDownButton',
        'title',
    ];
    static values = {
        isAnimating: {type: Boolean, default: false},
        id: {type: String},
    }
    static classes = [
        'maximized',
        'minimized',
    ];

    public connect(): void {
        this.setupMaxMinState();
        this.dispatch('connected');
    }

    public setBlockType(type: string): void {
        this.typeTarget.value = type;
        this.typeTarget.dispatchEvent(new Event('change', {bubbles: true, cancelable: true}));
    }

    public setOrder(order: number): void {
        this.orderTarget.value = order.toString();
        this.orderTarget.dispatchEvent(new Event('change', {bubbles: true, cancelable: true}));
    }

    public toggleTypeVisibility(): Promise<void> {
        return new Promise(async (resolve) => {
            let maximized = false;
            if (!this.isMaximized()) {
                await this.maximize();
                maximized = true;
            }
            const isVisible = getComputedStyle(this.typeRowTarget).display !== 'none';
            if (isVisible && maximized) {
                resolve();
                return;
            }
            this.isAnimatingValue = true;
            isVisible
                ? $(this.typeRowTarget).slideUp(SLIDE_DURATION, () => {
                    this.isAnimatingValue = false;
                    resolve();
                })
                : $(this.typeRowTarget).slideDown(() => {
                    this.isAnimatingValue = false;
                    resolve()
                });
        });
    }

    public moveUp(): void {
        this.dispatch('move-up', {detail: {order: +this.orderTarget.value}});
    }

    public moveDown(): void {
        this.dispatch('move-down', {detail: {order: +this.orderTarget.value}});
    }

    public remove(): void {
        this.dispatch('remove', {detail: {order: +this.orderTarget.value}});
    }

    public setupLayout(): void {
        this.hideEverything();
        const value = this.typeTarget.value;
        if (value.trim() === '') {
            return;
        }
        const option = this.typeTarget.querySelector(`option[value="${value}"]`);
        if (option === null) {
            return;
        }
        this.titleTarget.textContent = option.textContent;
        if (value === CmsContentBlockType.TEXT_EDITOR) {
            this.textRowTarget.style.display = '';
        } else if (value === CmsContentBlockType.EMBEDDED_VIDEO) {
            this.embeddedVideoUrlRowTarget.style.display = '';
        }
    }

    private hideEverything(): void {
        this.textRowTarget.style.display = 'none';
        this.embeddedVideoUrlRowTarget.style.display = 'none';
        this.fileRowTarget.style.display = 'none';
        this.filesRowTarget.style.display = 'none';
    }

    private setupMaxMinState(): void {
        this.isStoredMinimized() ? this.minimizeImmediately() : this.maximizeImmediately();
    }

    public maximize(): Promise<void> {
        return new Promise((resolve) => {
            if (this.isAnimatingValue || this.isMaximized()) {
                resolve();
                return;
            }
            this.isAnimatingValue = true;
            $(this.cardBodyTarget).slideDown(SLIDE_DURATION, () => {
                localStorage.setItem(this.getMaxMinStateStorageKey(), 'maximized');
                this.minimizeButtonTarget.style.display = '';
                this.maximizeButtonTarget.style.display = 'none';
                this.element.classList.remove(this.minimizedClass);
                this.element.classList.add(this.maximizedClass);
                this.isAnimatingValue = false;
                resolve();
            });
        });
    }

    public maximizeImmediately(): void {
        if (this.isAnimatingValue) {
            throw new InvalidStateException('Cannot maximize content block immediately while it is animating');
        }
        if (this.isMaximized()) {
            return;
        }
        localStorage.setItem(this.getMaxMinStateStorageKey(), 'maximized');
        this.cardBodyTarget.style.display = '';
        this.minimizeButtonTarget.style.display = '';
        this.maximizeButtonTarget.style.display = 'none';
        this.element.classList.remove(this.minimizedClass);
        this.element.classList.add(this.maximizedClass);
    }

    public minimize(): Promise<void> {
        return new Promise((resolve) => {
            if (this.isAnimatingValue || this.isMinimized()) {
                resolve();
                return;
            }
            this.isAnimatingValue = true;
            $(this.cardBodyTarget).slideUp(SLIDE_DURATION, () => {
                localStorage.setItem(this.getMaxMinStateStorageKey(), 'minimized');
                this.minimizeButtonTarget.style.display = 'none';
                this.maximizeButtonTarget.style.display = '';
                this.isAnimatingValue = false;
                this.element.classList.remove(this.maximizedClass);
                this.element.classList.add(this.minimizedClass);
                resolve();
            });
        });
    }

    public minimizeImmediately(): void {
        if (this.isAnimatingValue) {
            throw new InvalidStateException('Cannot minimize content block immediately while it is animating');
        }
        if (this.isMinimized()) {
            return;
        }
        localStorage.setItem(this.getMaxMinStateStorageKey(), 'minimized');
        this.cardBodyTarget.style.display = 'none';
        this.minimizeButtonTarget.style.display = 'none';
        this.maximizeButtonTarget.style.display = '';
        this.element.classList.remove(this.maximizedClass);
        this.element.classList.add(this.minimizedClass);
    }

    public isMaximized(): boolean {
        return this.cardBodyTarget.style.display !== 'none';
    }

    public isMinimized(): boolean {
        return !this.isMaximized();
    }

    private isStoredMinimized(): boolean {
        return localStorage.getItem(this.getMaxMinStateStorageKey()) === 'minimized';
    }

    private getMaxMinStateStorageKey(): string {
        return this.generateStorageKey('max-min-state');
    }

    private generateStorageKey(key: string): string {
        return `@cms-content-block-controller/${this.idValue}/${key}`;
    }

    public disableMoveUpButton(): void {
        this.isMoveUpButtonDisabled = true;
    }

    public enableMoveUpButton(): void {
        this.isMoveUpButtonDisabled = false;
    }

    public disableMoveDownButton(): void {
        this.isMoveDownButtonDisabled = true;
    }

    public enableMoveDownButton(): void {
        this.isMoveDownButtonDisabled = false;
    }

    public setupSettingsDropdown(): void {
        if (this.hasMoveUpButtonTarget) {
            this.isMoveDownButtonDisabled
                ? this.moveDownButtonTarget.setAttribute('disabled', 'disabled')
                : this.moveDownButtonTarget.removeAttribute('disabled');
        }
        if (this.hasMoveDownButtonTarget) {
            this.isMoveUpButtonDisabled
                ? this.moveUpButtonTarget.setAttribute('disabled', 'disabled')
                : this.moveUpButtonTarget.removeAttribute('disabled');
        }
    }
}

enum CmsContentBlockType
{
    TEXT_EDITOR = 'TEXT_EDITOR',
    EMBEDDED_VIDEO = 'EMBEDDED_VIDEO',
}
