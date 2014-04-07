require(['App/App'], function(App) {

    describe('Unit: just checking', function() {

        it('my expression', function() {
            assert.instanceOf(App, Angular, 'chai is an instance of tea');
        });
    });
});