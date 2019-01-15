Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'multitenancy-tool',
            path: '/multitenancy-tool',
            component: require('./components/Tool'),
        },
    ])
})
