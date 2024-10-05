export const createHTMLElementFromString = (html: string): HTMLElement => {
    const template = document.createElement('template');
    template.innerHTML = html.trim();
    return template.content.firstChild as HTMLElement;
};

export const createHTMLElementFromTemplate = (template: HTMLTemplateElement): HTMLElement => {
    return createHTMLElementFromString(template.innerHTML);
};

export const getHeightWithMargin = (element: HTMLElement): number => {
    const style = getComputedStyle(element);
    return element.offsetHeight + parseFloat(style.marginTop) + parseFloat(style.marginBottom);
};

export const getMarginX = (element: HTMLElement): number => {
    const style = getComputedStyle(element);
    return parseFloat(style.marginLeft) + parseFloat(style.marginRight);
};

export const getMarginY = (element: HTMLElement): number => {
    const style = getComputedStyle(element);
    return parseFloat(style.marginTop) + parseFloat(style.marginBottom);
};

export const getPaddingX = (element: HTMLElement): number => {
    const style = getComputedStyle(element);
    return parseFloat(style.paddingLeft) + parseFloat(style.paddingRight);
};

export const getPaddingY = (element: HTMLElement): number => {
    const style = getComputedStyle(element);
    return parseFloat(style.paddingTop) + parseFloat(style.paddingBottom);
};

export const proxyEvents = (events: string | string [], from: HTMLElement, to: HTMLElement, filter: ((event: Event) => boolean) | null = null): void => {
    if (typeof events === 'string') {
        events = [events];
    }
    events.forEach(eventType => {
        from.addEventListener(eventType, (event: Event) => {
            if (filter !== null && !filter(event)) {
                return;
            }
            const newEvent = copyEvent(event);
            to.dispatchEvent(newEvent);
        });
    });
};

export const parsePixels = (value: string): number => {
    return parseFloat(value.replace('px', ''));
};

export const copyEvent = (event: Event): Event => {
    const newEvent = new Event(event.type, {
        bubbles: event.bubbles,
        cancelable: event.cancelable,
        composed: event.composed,
    });
    for (const key in event) {
        if (event.hasOwnProperty(key)) {
            try {
                (newEvent as any)[key] = (event as any)[key];
            } catch (_) {
            }
        }
    }
    return newEvent;
};

export const remToPixels = (rem: number): number => {
    return rem * parseFloat(getComputedStyle(document.documentElement).fontSize);
};

export const extractFirstElementFromHtml = (html: string): HTMLElement => {
    const template = document.createElement('template');
    template.innerHTML = html;
    return template.content.firstElementChild as HTMLElement;
};

export const decodeHtmlEntities = (encodedString: string): string => {
    const textarea = document.createElement('textarea');
    textarea.innerHTML = encodedString;
    return textarea.value;
};

export const removeDuplicateIds = (element: HTMLElement): void => {
    const ids = new Set<string>();
    const allElements = element.querySelectorAll('[id]');
    allElements.forEach(el => {
        if (ids.has(el.id)) {
            el.remove();
        } else {
            ids.add(el.id);
        }
    });
};
