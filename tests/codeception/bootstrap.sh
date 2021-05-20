#!/bin/bash

export WPDESK_PLUGIN_SLUG=wp-desk-woocommerce-to-excel
export WPDESK_PLUGIN_TITLE="WP Desk WooCommerce To Excel"

export WOOTESTS_IP=${WOOTESTS_IP:wootests}

sh ./vendor/wpdesk/wp-codeception/scripts/common_bootstrap.sh
