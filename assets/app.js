import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

// // start the Stimulus application
// import './bootstrap';

// import './styles/app.scss';

// Importer Stimulus
import { Application } from "@hotwired/stimulus";
import { definitionsFromContext } from "@hotwired/stimulus-webpack-helpers";

// Initialisation de Stimulus
const application = Application.start();
const context = require.context("./controllers", true, /\.js$/);
application.load(definitionsFromContext(context));


// // Import your custom SCSS styles
import './styles/app.scss';

// // Import Bootstrap CSS and JS from node_modules
import './styles/bootstrap.min.css';
import './js/bootstrap.bundle.min.js';

// // Import your custom JavaScript
import './js/script.js';

