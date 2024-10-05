import {Controller} from '@hotwired/stimulus';
import {useThrottle} from 'stimulus-use';
import {createHTMLElementFromTemplate} from 'app/utils/dom';
import {SLIDE_DURATION} from 'app/config/variables';
import {
    computePosition,
    flip,
    shift,
    offset, Placement,
} from '@floating-ui/dom';
import {appendToRoot, Root} from 'app/utils/element-placer';

export default class DropdownController extends Controller<HTMLElement>
{
    declare private removeOnHideValue: boolean;
    declare private placementValue: Placement;
    declare private rootValue: Root;

    private templateTarget!: HTMLTemplateElement;

    private isOpenClass!: string;

    private dataElement: HTMLElement | null = null;
    private isOpen: boolean = false;

    static values = {
        removeOnHide: {type: Boolean, default: false},
        placement: {type: String, default: 'bottom-start'},
        root: {type: String, default: 'body'},
    };
    static classes = [
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
    }

    public async open(): Promise<void> {
        return new Promise(async (resolve) => {
            if (this.isOpen) {
                resolve();
                return;
            }
            this.dispatch('opening');
            const dropdown = this.getDataElement();
            dropdown.style.display = 'block';
            dropdown.style.visibility = 'hidden';
            this.isOpen = true;
            this.element.classList.add(this.isOpenClass);
            dropdown.classList.add(this.isOpenClass);
            await this.computePosition();
            dropdown.style.display = 'none';
            dropdown.style.visibility = '';
            $(dropdown).slideDown(SLIDE_DURATION, () => {
                this.dispatch('opened');
                resolve();
            });
        });
    }

    public close(): Promise<void> {
        return new Promise((resolve) => {
            if (!this.isOpen) {
                resolve();
                return;
            }
            this.dispatch('closing');
            const dropdown = this.getDataElement();
            $(dropdown).slideUp(SLIDE_DURATION, () => {
                this.isOpen = false;
                this.element.classList.remove(this.isOpenClass);
                dropdown.classList.remove(this.isOpenClass);
                if (this.removeOnHideValue) {
                    dropdown.remove();
                    this.dataElement = null;
                }
                this.dispatch('closed');
                resolve();
            });
        });
    }

    public toggle(): void {
        this.isOpen ? this.close() : this.open();
    }

    public async computePosition(): Promise<void> {
        if (!this.isOpen) {
            return;
        }
        const dropdown = this.getDataElement();
        const position = await computePosition(this.element, dropdown, {
            placement: this.placementValue,
            middleware: [offset(5), flip(), shift({padding: 5})],
        });
        dropdown.style.left = `${position.x}px`;
        dropdown.style.top = `${position.y}px`;
    }

    public onGlobalClick(event: Event): Promise<void> {
        return new Promise(async (resolve) => {
            if (!this.isOpen) {
                resolve();
                return;
            }
            const target = event.target as HTMLElement;
            if (target === this.element || this.element.contains(target)) {
                resolve();
                return;
            }
            if (this.dataElement !== null && (target === this.dataElement || this.dataElement.contains(target))) {
                resolve();
                return;
            }
            await this.close();
            resolve();
        });
    }

    private getDataElement(): HTMLElement {
        if (this.dataElement !== null) {
            return this.dataElement;
        }
        const element = createHTMLElementFromTemplate(this.templateTarget);
        element.style.display = 'none';
        appendToRoot(this.rootValue, element, this.element);
        element.addEventListener('click', (event) => {
            const target = event.target;
            if (target instanceof HTMLElement && (target.hasAttribute('data-dropdown-close-on-click') || target.closest('[data-dropdown-close-on-click]') !== null)) {
                setTimeout(() => this.close());
            }
        });
        this.dataElement = element;
        this.dispatch('dropdown-created');
        return element;
    }
}
