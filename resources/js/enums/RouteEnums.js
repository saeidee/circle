export default new class RouteEnums {

    constructor() {
        this.CAMPAIGN_CREATE = '/campaign/create/:id?';
        this.CAMPAIGN_ADD_PRODUCTS = '/campaign/app-products/:id?';
        this.CAMPAIGN_CREATE_DEMO = '/campaign-demo/create';
        this.CAMPAIGN_INDEX_DEMO = '/campaign-demo';
        this.PRODUCT_INDEX_DEMO = '/product-demo';
        this.PRODUCT_CREATE_DEMO = '/product-demo/create';
        this.PRODUCT_SHOW_DEMO = '/product-demo/show/:id?';
        this.CAMPAIGN_SHOW_DEMO = '/campaigns-demo/show/:id?';
        this.SETTINGS = '/settings';
    }
}
