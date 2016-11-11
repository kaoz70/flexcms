describe('Service: App.Template', function () {

    // load the service's module
    beforeEach(module('App'));

    // instantiate service
    var service;

    //update the injection
    beforeEach(inject(function (_Template_) {
        service = _Template_;
    }));

    /**
     * @description
     * Sample test case to check if the service is injected properly
     * */
    it('should be injected and defined', function () {
        expect(service).toBeDefined();
    });
});
