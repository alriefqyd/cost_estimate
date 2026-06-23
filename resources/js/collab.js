/**
 * EstimateCollab — thin Yjs wrapper used by estimate_discipline.js
 *
 * Exposes window.EstimateCollab so plain jQuery code can use CRDT
 * real-time sync without knowing anything about Yjs internals.
 */
import * as Y from 'yjs'
import { WebsocketProvider } from 'y-websocket'

;(function () {
    var ydoc     = null
    var provider = null
    var yrows    = null

    window.EstimateCollab = {

        /**
         * Open a Yjs doc and connect to the y-websocket server.
         * Call once per page load on the estimate discipline page.
         */
        connect: function (projectId, wsUrl) {
            ydoc     = new Y.Doc()
            yrows    = ydoc.getMap('rows')
            provider = new WebsocketProvider(wsUrl, 'estimate-' + projectId, ydoc)
            return this
        },

        /**
         * Register a callback for remote row changes/deletes.
         * callback(type, uid, payload)
         *   type    = 'changed' | 'deleted'
         *   uid     = row's unique_identifier string
         *   payload = full broadcast payload object (matches buildBroadcastPayload)
         */
        onRowChange: function (callback) {
            if (!yrows) return
            yrows.observe(function (event, transaction) {
                if (transaction.local) return   // own changes — already reflected in DOM
                event.changes.keys.forEach(function (change, uid) {
                    if (change.action === 'delete') {
                        callback('deleted', uid, null)
                    } else {
                        var yrow = yrows.get(uid)
                        if (!yrow) return
                        var data = {}
                        yrow.forEach(function (v, k) { data[k] = v })
                        callback('changed', uid, data)
                    }
                })
            })
        },

        /**
         * Push a row update into Yjs so all other connected clients see it.
         * payload = the JSON object returned by the server's buildBroadcastPayload().
         */
        setRow: function (uid, payload) {
            if (!yrows || !ydoc) return
            ydoc.transact(function () {
                var yrow = new Y.Map()
                Object.keys(payload).forEach(function (k) { yrow.set(k, payload[k]) })
                yrows.set(uid, yrow)
            }, 'local')
        },

        /**
         * Broadcast a row deletion to all other clients.
         */
        removeRow: function (uid) {
            if (!yrows || !ydoc) return
            ydoc.transact(function () { yrows.delete(uid) }, 'local')
        },

        /**
         * Subscribe to WebSocket connection status changes.
         * callback('connected' | 'disconnected' | 'connecting')
         */
        onStatus: function (callback) {
            if (!provider) return
            provider.on('status', function (e) { callback(e.status) })
            // 'sync' fires when the initial state has arrived from the server
            provider.on('sync', function (isSynced) {
                if (isSynced) callback('connected')
            })
        },
    }
})()
