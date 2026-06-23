import React from 'react'
import { createRoot } from 'react-dom/client'
import App from './App'
import './styles.css'

const el = document.getElementById('estimate-react-root')
if (el) {
    const init = window.__ESTIMATE_INIT__ || {}
    createRoot(el).render(<App {...init} />)
}
