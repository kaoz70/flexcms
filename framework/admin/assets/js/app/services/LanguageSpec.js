describe('Service: app.Language', function () {

    // load the service's module
    beforeEach(module('app'));

    // instantiate service
    var service;

    //update the injection
    beforeEach(inject(function (_Language_) {
        service = _Language_;
    }));

    /**
     * @description
     * Sample test case to check if the service is injected properly
     * */
    it('should be injected and defined', function () {
        expect(service).toBeDefined();
    });
});
