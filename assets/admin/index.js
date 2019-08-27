// Polyfills
import 'regenerator-runtime/runtime';
import 'url-search-params-polyfill';
import 'whatwg-fetch';
import 'core-js/fn/array/includes';
import 'core-js/fn/array/find';
import 'core-js/fn/array/find-index';
import 'core-js/fn/array/fill';
import 'core-js/fn/array/from';
import 'core-js/fn/object/assign';
import 'core-js/fn/object/values';
import 'core-js/fn/promise';
import 'core-js/fn/string/code-point-at';
import 'core-js/fn/string/includes';
import 'core-js/fn/symbol';
import 'unorm';

// Bundles
import {startAdmin} from 'sulu-admin-bundle';
import 'sulu-audience-targeting-bundle';
import 'sulu-category-bundle';
import 'sulu-contact-bundle';
import 'sulu-custom-url-bundle';
import 'sulu-media-bundle';
import 'sulu-page-bundle';
import 'sulu-preview-bundle';
import 'sulu-route-bundle';
import 'sulu-search-bundle';
import 'sulu-security-bundle';
import 'sulu-snippet-bundle';
import 'sulu-website-bundle';

// Implement custom extensions here

// Start admin application
startAdmin();
