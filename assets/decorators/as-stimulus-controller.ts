import 'reflect-metadata/lite';

export const asStimulusControllerMetadataId = '_as_stimulus_controller';

export function AsStimulusController(identifier: string) {
    return function (constructor: Function) {
        Reflect.defineMetadata(asStimulusControllerMetadataId, identifier, constructor);
    };
}
