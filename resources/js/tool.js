Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'multitenancy-nova-tool',
            path: '/multitenancy-nova-tool',
            component: require('./components/Tool'),
        },
    ])
})
