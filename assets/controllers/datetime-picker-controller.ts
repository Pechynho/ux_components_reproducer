import {Controller} from '@hotwired/stimulus';
import flatpickr from 'flatpickr';
import darkModeCss from '!!css-loader?{"sourceMap":false,"exportType":"string"}!flatpickr/dist/themes/dark.css';
import altModeCss from '!!css-loader?{"sourceMap":false,"exportType":"string"}!flatpickr/dist/themes/material_blue.css';
import {darkMode} from "app/utils/dark-mode";
import Instance = flatpickr.Instance;

const stylesId = 'datetime-picker-controller-styles';

export default class DatetimePickerController extends Controller<HTMLInputElement>
{
    declare private formatValue: string;
    declare private alternativeFormatValue: string;
    declare private hasAlternativeFormatValue: boolean;
    declare private enableTimeValue: boolean;
    declare private time24hrValue: boolean;

    private component: Instance | null = null;

    static values = {
        format: {type: String, default: 'Y-m-d H:i'},
        alternativeFormat: String,
        enableTime: {type: Boolean, default: false},
        time24hr: {type: Boolean, default: true},
    }

    public connect(): void {
        this.setupStyles();
        const modalDialog = this.element.closest<HTMLElement>('.modal-dialog');
        /**
         * Make proxy with preserved type and style display=none so Live Component morphdom correctly
         * morphs the element value when it's changed via Component.
         */
        const backupType = this.element.type;
        this.component = flatpickr(this.element, {
            dateFormat: this.formatValue,
            enableTime: this.enableTimeValue,
            time_24hr: this.time24hrValue,
            altInput: this.hasAlternativeFormatValue,
            altFormat: this.hasAlternativeFormatValue ? this.alternativeFormatValue : undefined,
            appendTo: modalDialog !== null ? modalDialog : undefined,
        });
        if (this.hasAlternativeFormatValue) {
            this.element.style.display = 'none';
            this.element.type = backupType;
        }
    }

    public setupStyles(): void {
        let styles = document.getElementById(stylesId);
        if (styles === null) {
            styles = document.createElement('style');
            styles.id = stylesId;
            document.head.appendChild(styles);
        }
        if (darkMode.isDarkMode()) {
            if (styles.dataset.theme !== 'dark') {
                styles.innerHTML = darkModeCss;
                styles.dataset.theme = 'dark';
            }
            return;
        }
        if (styles.dataset.theme !== 'light') {
            styles.innerHTML = altModeCss;
            styles.dataset.theme = 'light';
        }
    }

    public disconnect(): void {
        this.component?.destroy();
        if (this.hasAlternativeFormatValue) {
            this.element.style.display = '';
        }
    }

    public synchronize(): void {
        this.component?.setDate(this.element.value, false);
    }
}
