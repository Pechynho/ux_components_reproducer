import {Controller} from '@hotwired/stimulus';
import {SLIDE_DURATION} from 'app/config/variables';
import InvalidStateException from "app/errors/invalid-state-exception";

export default class AlertController extends Controller<HTMLElement>
{
    declare private autoCloseTimeoutValue: number;
    declare private hasAutoCloseTimeoutValue: boolean;
    declare private removeAfterCloseValue: boolean;
    declare private isAnimatingValue: boolean;
    declare private isOpenValue: boolean;

    private timeout: NodeJS.Timeout | null = null;

    static values = {
        autoCloseTimeout: Number,
        removeAfterClose: {type: Boolean, default: false},
        isAnimating: {type: Boolean, default: false},
        isOpen: {type: Boolean, default: true},
    };

    public connect() {
        if (this.hasAutoCloseTimeoutValue) {
            this.timeout = setTimeout(async () => {
                await this.close();
            }, this.autoCloseTimeoutValue);
        }
    }

    public disconnect() {
        if (this.timeout !== null) {
            clearTimeout(this.timeout);
        }
    }

    public get isOpen(): boolean {
        return this.isOpenValue;
    }

    public toggle(): Promise<void> {
        return this.isOpenValue ? this.close() : this.open();
    }

    public toggleImmediately(): void {
        this.isOpenValue ? this.closeImmediately() : this.openImmediately();
    }

    public open(): Promise<void> {
        return new Promise((resolve) => {
            if (this.isAnimatingValue || this.isOpenValue) {
                resolve();
                return;
            }
            this.isAnimatingValue = true;
            this.dispatch('opening');
            $(this.element).slideDown(SLIDE_DURATION, () => {
                this.isOpenValue = true;
                this.isAnimatingValue = false;
                this.dispatch('opened');
                resolve();
            });
        });
    }

    public openImmediately(): void {
        if (this.isAnimatingValue) {
            throw new InvalidStateException('Cannot open alert immediately while it is animating');
        }
        if (this.isOpenValue) {
            return;
        }
        this.isOpenValue = true;
        this.element.style.display = '';
        this.dispatch('opened');
    }

    public close(): Promise<void> {
        return new Promise((resolve) => {
            if (this.isAnimatingValue || !this.isOpenValue) {
                resolve();
                return;
            }
            this.isAnimatingValue = true;
            this.dispatch('closing');
            $(this.element).slideUp(SLIDE_DURATION, () => {
                this.isOpenValue = false;
                this.isAnimatingValue = false;
                this.dispatch('closed');
                if (this.removeAfterCloseValue) {
                    this.dispatch('remove');
                    this.element.remove();
                }
                resolve();
            });
        });
    }

    public closeImmediately(): void {
        if (this.isAnimatingValue) {
            throw new InvalidStateException('Cannot close alert immediately while it is animating');
        }
        if (!this.isOpenValue) {
            return;
        }
        this.isOpenValue = false;
        this.element.style.display = 'none';
        this.dispatch('closed');
        if (this.removeAfterCloseValue) {
            this.dispatch('remove');
            this.element.remove();
        }
    }
}
