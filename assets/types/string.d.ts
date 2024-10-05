interface StringConstructor {
    isEmptyOrWhiteSpace(subject: string): boolean;

    isNullOrWhiteSpace(subject: string | null): boolean;

    isEmpty(subject: string): boolean;

    decodeHtmlEntities(subject: string): string;
}

interface String {
    firstToUpper(): string;

    firstToLower(): string;
}
