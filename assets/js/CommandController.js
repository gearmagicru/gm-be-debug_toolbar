/*!
 * Контроллер команды.
 * Модуль "Панель инструментов отладчика".
 * Copyright 2015 Вeб-студия GearMagic. Anton Tivonenko <anton.tivonenko@gmail.com>
 * https://gearmagic.ru/license/
 */

Ext.define('Gm.be.debug_toolbar.CommandController', {
    extend: 'Gm.view.form.PanelController',
    alias: 'controller.gm-be-debug_toolbar-command',

    /**
     * Срабатывает при клике на триггер команды.
     * @param {Ext.form.field.TextField} me
     */
    onRun: function (me) { this.doRun(me.value); },

    /**
     * Правка маршрута модуля / расширения.
     * @param {Ext.form.field.TextField} me
     * @param {Ext.event.Event} e
     * @param {Object} eOpts
     */
     keyRun: function (me, e, eOpts) {
        if (e.keyCode === 13) { this.doRun(me.value); }
    },

    /**
     * Выполнение команды.
     * Пример: 'foo/bar?type=cool width=100 height=100', результат:
     * {"url": foo/bar?type=cool, "params": {width: 100, height: 100}}.
     * @param {String} command Команда.
     */
    doRun: function (command) {
        let route = command,
            params = {},
            chunks = command.split(' ');
        if (chunks.length > 1) {
            route = chunks[0];
            chunks.shift();            
            chunks.forEach((chunk) => {
                let vk = chunk.split('=');
                params[vk[0]] = vk[1];
            });
        }
        Gm.app.widget.load('@backend/' + route, params);
    }
});
