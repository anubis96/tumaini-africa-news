import { startStimulusApp } from '@symfony/stimulus-bridge';
import carousel_controller from './controllers/carousel_controller';
import scroll_controller from './controllers/scroll_controller';
import advertise_controller from './controllers/advertise_controller';
import articleshare_controller from './controllers/articleshare_controller';
import audioplayer_controller from './controllers/audioplayer_controller';

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(import.meta.webpackContext('@symfony/stimulus-bridge/lazy-controller-loader!./controllers', {
    recursive: true,
    regExp: /\.[jt]sx?$/,
}));
// register any custom, 3rd party controllers here
app.register('advertise-modal', advertise_controller);
app.register('carousel', carousel_controller);
app.register('scroll-carousel', scroll_controller);
app.register('article-share', articleshare_controller);
app.register('audio-player', audioplayer_controller);