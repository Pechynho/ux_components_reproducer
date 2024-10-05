import {Controller} from '@hotwired/stimulus';

export default class EventDispatcherController extends Controller<HTMLElement>
{
    declare private eventsValue: Events;

    private handlers: { [key: string]: Handler[] } = {};

    static values = {
        events: {type: Object, default: {}},
    };

    public connect() {
        for (const [event, eventsToDispatch] of Object.entries(this.eventsValue)) {
            if (!this.handlers[event]) {
                this.handlers[event] = [];
            }
            for (const eventToDispatch of eventsToDispatch) {
                const handler = (event: Event) => this.dispatch(eventToDispatch, {
                    bubbles: true,
                    detail: {event: event},
                    prefix: '',
                });
                this.handlers[event].push(handler);
                this.element.addEventListener(event, handler);
            }
        }
    }

    public disconnect() {
        for (const [event, handlers] of Object.entries(this.handlers)) {
            for (const handler of handlers) {
                this.element.removeEventListener(event, handler);
            }
        }
        this.handlers = {};
    }
}

type Events = {
    [key: string]: string[];
}

type Handler = (event: Event) => void;
