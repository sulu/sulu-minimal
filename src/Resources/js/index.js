// @flow
import {bundleReady} from 'sulu-admin-bundle/services';
import {fieldRegistry} from 'sulu-admin-bundle/containers';
import ProductAttributes from './components/ProductAttributes';

fieldRegistry.add('product_attributes', ProductAttributes);

bundleReady();
