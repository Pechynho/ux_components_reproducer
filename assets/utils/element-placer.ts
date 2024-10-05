import NotImplementedException from 'app/errors/not-implemented-exception';

export type Root = 'body' | 'parent' | 'toggler' | `closest|${string}` | `query|${string}` | `id|${string}`;

export const appendToRoot = (root: Root, element: HTMLElement, toggler: HTMLElement) => {
    if (root === 'body') {
        document.body.appendChild(element);
    } else if (root === 'parent') {
        const parent = toggler.parentElement ?? document.body;
        parent.appendChild(element);
    } else if (root === 'toggler') {
        toggler.appendChild(element);
    } else if (root.startsWith('closest|')) {
        const selector = root.split('|')[1];
        const parent = toggler.closest(selector) ?? document.body;
        parent.appendChild(element);
    } else if (root.startsWith('query|')) {
        const selector = root.split('|')[1];
        const parent = document.querySelector(selector) ?? document.body;
        parent.appendChild(element);
    } else if (root.startsWith('id|')) {
        const id = root.split('|')[1];
        const parent = document.getElementById(id) ?? document.body;
        parent.appendChild(element);
    } else {
        throw new NotImplementedException();
    }
};
