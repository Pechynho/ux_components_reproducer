import {Cookie, SameSite} from "app/utils/cookie";

class DarkMode
{
    private cookie: Cookie;

    constructor() {
        this.cookie = new Cookie('dark-mode', 365, '/', null, false, SameSite.Lax);
    }

    public isDarkMode(): boolean {
        return this.cookie.get() === 'on';
    }

    public isLightMode(): boolean {
        return this.cookie.get() === 'off';
    }

    public setDarkMode(): void {
        const html = document.querySelector('html')!;
        html.classList.add('dark');
        this.cookie.set('on');
        this.dispatchChangedEvent();
        this.dispatchEvent('enabled');
    }

    public setLightMode(): void {
        const html = document.querySelector('html')!;
        html.classList.remove('dark');
        this.cookie.set('off');
        this.dispatchChangedEvent();
        this.dispatchEvent('disabled');
    }

    public toggle(): void {
        if (this.isDarkMode()) {
            this.setLightMode();
            return;
        }
        this.setDarkMode();
    }

    private dispatchChangedEvent(): void {
        this.dispatchEvent('changed');
    }

    private dispatchEvent(name: string): void {
        if (!name.startsWith('dark-mode:')) {
            name = 'dark-mode:' + name;
        }
        window.dispatchEvent(new Event(name, {
            bubbles: true,
            cancelable: true,
        }));
    }
}

export const darkMode = new DarkMode();
