import {Controller} from "@hotwired/stimulus";
import InvalidStateException from "app/errors/invalid-state-exception";

export default class ValueChangedByMorphdomController extends Controller<HTMLElement>
{
    private fingerprint: string | null = null;

    static values = {
        timestamp: {type: Number, default: 0},
    }

    public connect(): void {
        this.storeValue();
    }

    public disconnect() {
        this.fingerprint = null;
    }

    public timestampValueChanged(_: number, previousTimestamp: number): void {
        if (previousTimestamp === 0) {
            return;
        }
        const fingerprint = this.computeFingerprint();
        if (this.fingerprint === fingerprint) {
            return;
        }
        this.fingerprint = fingerprint;
        this.element.dispatchEvent(new Event('value-changed-by-morphdom', {bubbles: true}));
    }

    public storeValue(): void {
        this.fingerprint = this.computeFingerprint();
    }

    private computeFingerprint(): string {
        if (this.element instanceof HTMLSelectElement) {
            if (this.element.hasAttribute('multiple')) {
                const values = Array.from(this.element.querySelectorAll<HTMLOptionElement>('option:checked')).map((option) => option.value);
                return values.sort().join(',');
            }
            return this.element.value;
        }
        if (this.element instanceof HTMLInputElement) {
            if (this.element.type === 'checkbox') {
                return this.element.checked ? '1' : '0';
            }
            if (this.element.type === 'radio') {
                return this.element.checked ? this.element.value : '';
            }
            if (this.element.type === 'file') {
                throw new InvalidStateException('File input is not supported');
            }
            return this.element.value;
        }
        if (this.element instanceof HTMLTextAreaElement) {
            return this.element.value;
        }
        throw new InvalidStateException('Element type not supported');
    }
}
