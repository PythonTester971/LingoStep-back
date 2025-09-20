import { Application } from "@hotwired/stimulus"
import FormCollectionController from "./form-collection_controller"

const application = Application.start()
application.register("form-collection", FormCollectionController)

window.Stimulus = application  