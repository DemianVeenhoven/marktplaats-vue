import {createVuePlugin} from 'vite-plugin-vue2';
import copy from 'rollup-plugin-copy';

export default ({command}) => {
    const production = command !== 'serve';

    const plugins = [
        copy({
            targets: [
                {src: 'resources/images', dest: 'public'},
            ],
            hook: 'writeBundle',
        }),
        createVuePlugin({
            vueTemplateOptions: {
                transformAssetUrls: false,
            },
        }),
    ];

    return {
        base: production ? '/js/' : '',
        build: {
            assetsInclude: [],
            manifest: true,
            outDir: 'public/js',
            rollupOptions: {
                input: 'resources/js/app.js',
            },
        },
        plugins,
        server: {
            port: 3000,
        },

        // Vite bugs if you build out dir is inside the public dir so we change that to something else
        publicDir: 'random_non_existent_folder',
    };
};
