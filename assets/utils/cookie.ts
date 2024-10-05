export enum SameSite {
    Strict = 'Strict',
    Lax = 'Lax',
    None = 'None',
}

export class Cookie {
    private readonly _cookieName: string;
    private readonly _expirationDays: number | null;
    private readonly _path: string | null;
    private readonly _domain: string | null;
    private readonly _secure: boolean;
    private readonly _sameSite: SameSite;

    constructor(
        cookieName: string,
        expirationDays: number | null = null,
        path: string | null = null,
        domain: string | null = null,
        secure: boolean = false,
        sameSite: SameSite = SameSite.Strict,
    ) {
        this._cookieName = cookieName;
        this._expirationDays = expirationDays;
        this._path = path;
        this._domain = domain;
        this._secure = secure;
        this._sameSite = sameSite;
    }

    public get cookieName(): string {
        return this._cookieName;
    }

    public get expirationDays(): number | null {
        return this._expirationDays;
    }

    public get path(): string | null {
        return this._path;
    }

    public get domain(): string | null {
        return this._domain;
    }

    public get secure(): boolean {
        return this._secure;
    }

    public get sameSite(): SameSite {
        return this._sameSite;
    }

    set(value: string): void {
        const date = new Date();
        if (this._expirationDays !== null) {
            date.setTime(date.getTime() + this._expirationDays * 24 * 60 * 60 * 1000);
        }

        const expires = this._expirationDays !== null ? `expires=${date.toUTCString()}` : '';
        const path = this._path !== null ? `path=${this._path}` : '';
        const domain = this._domain !== null ? `domain=${this._domain}` : '';
        const secure = this._secure ? 'secure' : '';
        const sameSite = `SameSite=${this._sameSite}`;

        document.cookie = `${this._cookieName}=${value};${expires};${path};${domain};${secure};${sameSite}`;
    }

    get(): string | null {
        const name = `${this._cookieName}=`;
        const cookieArray = document.cookie.split(';');
        for (let i = 0; i < cookieArray.length; i++) {
            const cookie = cookieArray[i].trim();
            if (cookie.indexOf(name) === 0) {
                return cookie.substring(name.length);
            }
        }
        return null;
    }
}
