import { Application } from '@hotwired/stimulus';
import CsrfProtectionController from './controllers/csrf_protection_controller';
import FormCollectionController from './controllers/form-collection_controller';
import HelloController from './controllers/hello_controller';

const application = Application.start();

application.register('csrf-protection', CsrfProtectionController);
application.register('form-collection', FormCollectionController);
application.register('hello', HelloController);

export default application;