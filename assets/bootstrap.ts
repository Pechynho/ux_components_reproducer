import '@fortawesome/fontawesome-free/css/all.css';
import 'app/styles/bootstrap.scss';
import 'app/extension/_extensions';
import {startStimulusApp} from '@symfony/stimulus-bridge';
import {definitionsFromContext} from "@hotwired/stimulus-webpack-helpers";
import 'reflect-metadata/lite';
import {asStimulusControllerMetadataId} from "app/decorators/as-stimulus-controller";

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/,
));

// Registers Stimulus controllers from components/ directory
const definitions = definitionsFromContext(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!../components',
    true,
    /\.[jt]sx?$/,
));
for (const definition of definitions) {
    const controller = definition.controllerConstructor;
    const identifier = Reflect.getMetadata(asStimulusControllerMetadataId, controller);
    if (typeof identifier === 'string' && identifier.trim() !== '') {
        definition.identifier = identifier.trim();
        continue;
    }
    definition.identifier = definition.identifier.split('--').pop() as string;
}
app.load(definitions);
