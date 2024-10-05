import 'reflect-metadata/lite';

export const asStimulusControllerMetadataId = '@AsStimulusController';

export function AsStimulusController(identifier: string) {
    return function (constructor: Function) {
        Reflect.defineMetadata(asStimulusControllerMetadataId, identifier, constructor);
    };
}
