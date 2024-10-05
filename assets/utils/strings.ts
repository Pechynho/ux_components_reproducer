import {escapeForRegex} from 'app/utils/regex';

export const replaceAll = (subject: string, replacements: Record<string, string>): string => {
    for (const [search, replace] of Object.entries(replacements)) {
        subject = subject.replace(new RegExp(escapeForRegex(search), 'g'), replace);
    }
    return subject;
};
