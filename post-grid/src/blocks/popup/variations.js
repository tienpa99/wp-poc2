/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { applyFilters } from '@wordpress/hooks';

let isProFeature = applyFilters('isProFeature', true);



/**
 * Template option choices for predefined columns layouts.
 */
const variations = [

    {
        name: 'layout-1',
        title: __('layout-1'),
        description: __('layout-1'),

        isPro: false,
        wrapObj: { "options": { "tag": "div", "class": "grid-item-wrap" }, "styles": { gridTemplateColumns: { Desktop: '1fr ' }, gap: { Desktop: '1em' }, display: { Desktop: 'grid' }, } },
        innerBlocks: [
            ['post-grid/grid-wrap-item', { "wrapper": { "options": { "tag": "div", "class": "grid-item-wrap" }, "styles": { backgroundColor: { Desktop: '#2563eb24' }, } } }],
            ['post-grid/grid-wrap-item', { "wrapper": { "options": { "tag": "div", "class": "grid-item-wrap" }, "styles": { backgroundColor: { Desktop: '#2563eb24' }, } } }],

        ],
        scope: ['block'],
        icon: (
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 80"><rect fill="#1d4ed8" x="41.67" y="13.33" width="76.67" height="23.34" /><rect fill="#1d4ed8" x="41.67" y="43.33" width="76.67" height="23.34" /></svg>
        ),

    },















];

export default variations;