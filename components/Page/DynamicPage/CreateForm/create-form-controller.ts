import {Controller} from "@hotwired/stimulus";
import {AsStimulusController} from "app/decorators/as-stimulus-controller";

@AsStimulusController('dynamic-page-create-form')
export default class CreateFormController extends Controller
{
    declare private accessibleToRowTarget: HTMLInputElement;
    declare private accessibleFromRowTarget: HTMLElement;
    declare private restrictAccessTarget: HTMLInputElement;

    static targets = [
        'restrictAccess',
        'accessibleFromRow',
        'accessibleToRow',
    ];

    public onRestrictAccessChange(): void {
        if (this.restrictAccessTarget.checked) {
            this.accessibleFromRowTarget.style.display = '';
            this.accessibleToRowTarget.style.display = '';
        } else {
            this.accessibleFromRowTarget.style.display = 'none';
            this.accessibleToRowTarget.style.display = 'none';
        }
    }
}
