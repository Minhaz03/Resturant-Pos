import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Import local dependencies
import 'bootstrap';

import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import 'select2';

import * as FilePond from 'filepond';
window.FilePond = FilePond;

import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
window.FilePondPluginImagePreview = FilePondPluginImagePreview;

import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
window.FilePondPluginFileValidateSize = FilePondPluginFileValidateSize;

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
window.FilePondPluginFileValidateType = FilePondPluginFileValidateType;

import Swal from 'sweetalert2';
window.Swal = Swal;

import Chart from 'chart.js/auto';
window.Chart = Chart;
