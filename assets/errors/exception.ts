export default class Exception extends Error
{
    private readonly _previous: Error | null;
    public static readonly defaultMessage: string | null = null;

    public constructor(message: string | undefined | Error = undefined, previous: Error | null = null) {
        super();
        if (message instanceof Error) {
            previous = message;
            message = message.message;
        }
        if (typeof message === 'string') {
            this.message = message;
        }
        this._previous = previous;
        if (String.isNullOrWhiteSpace(this.message)) {
            const self = this.constructor as typeof Exception;
            const defaultMessage = self.defaultMessage;
            if (!String.isNullOrWhiteSpace(defaultMessage)) {
                this.message = defaultMessage as string;
            }
        }
    }

    get previous(): Error | null {
        return this._previous;
    }
}
