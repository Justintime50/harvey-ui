import "@popperjs/core";
import "bootstrap";

import axios from "axios";
import Chart from "chart.js/auto";

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = axios;
window.Chart = Chart;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
