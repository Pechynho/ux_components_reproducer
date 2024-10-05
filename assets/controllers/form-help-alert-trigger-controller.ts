import {Controller} from "@hotwired/stimulus";
import AlertController from "components/Alert/alert-controller";

export default class FormHelpAlertTriggerController extends Controller
{
    declare private idValue: string;

    static values = {
        id: {type: String},
    }

    public connect(): void {
        setTimeout(() => {
            if (this.isStoredClosed()) {
                this.getAlert()?.closeImmediately();
            }
        });
    }

    private getAlert(): AlertController | null {
        const element = document.getElementById(this.idValue);
        if (element === null) {
            return null;
        }
        return this.application.getControllerForElementAndIdentifier(element, 'alert') as AlertController | null;
    }

    public async toggle(): Promise<void> {
        this.getAlert()?.toggle();
    }

    public opAlertClosed(event: CustomEvent): void {
        if (event.target === this.getAlert()?.element) {
            localStorage.setItem(this.getStorageKey(), 'false');
        }
    }

    public onAlertOpened(event: CustomEvent): void {
        if (event.target === this.getAlert()?.element) {
            localStorage.setItem(this.getStorageKey(), 'true');
        }
    }

    private isStoredClosed(): boolean {
        return localStorage.getItem(this.getStorageKey()) === 'false';
    }

    private getStorageKey(): string {
        return `@form-alert-help-trigger-controller/${this.idValue}/visible`;
    }
}
