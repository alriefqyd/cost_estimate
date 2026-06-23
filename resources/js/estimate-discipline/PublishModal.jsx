import React, { useEffect, useRef } from 'react'

function fmt(val) {
    if (!val || isNaN(val)) return '0,00'
    const [int, dec] = Number(val).toFixed(2).split('.')
    return int.replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ',' + dec
}

export default function PublishModal({ userDiscipline, contingency, publishing, onConfirm, onClose }) {
    const confirmRef = useRef(null)

    // Focus confirm button on open; Escape to close
    useEffect(() => {
        confirmRef.current?.focus()
        const onKey = e => { if (e.key === 'Escape' && !publishing) onClose() }
        window.addEventListener('keydown', onKey)
        return () => window.removeEventListener('keydown', onKey)
    }, [publishing, onClose])

    const discLabel = userDiscipline
        ? userDiscipline.charAt(0).toUpperCase() + userDiscipline.slice(1)
        : ''

    return (
        <div className="pm-overlay" onClick={() => !publishing && onClose()}>
            <div className="pm-panel" role="dialog" aria-modal="true" onClick={e => e.stopPropagation()}>

                {/* ── Icon header ── */}
                <div className="pm-icon-wrap">
                    <div className="pm-icon-ring">
                        <i className="fa fa-paper-plane pm-icon" />
                    </div>
                </div>

                {/* ── Title + body ── */}
                <div className="pm-body">
                    <h5 className="pm-title">Publish Estimate</h5>
                    <p className="pm-subtitle">
                        Submit the <strong>{discLabel}</strong> discipline estimate for review.
                    </p>

                    <div className="pm-info-box">
                        <div className="pm-info-row">
                            <span className="pm-info-label">Discipline</span>
                            <span className="pm-info-value">{discLabel}</span>
                        </div>
                        <div className="pm-info-row">
                            <span className="pm-info-label">Contingency</span>
                            <span className="pm-info-value">{fmt(contingency)}%</span>
                        </div>
                    </div>

                    <div className="pm-warning">
                        <i className="fa fa-lock pm-warning-icon" />
                        <span>
                            Your estimate will be <strong>locked for editing</strong> until the reviewer
                            approves, requests changes, or rejects it.
                        </span>
                    </div>
                </div>

                {/* ── Actions ── */}
                <div className="pm-footer">
                    <button
                        className="pm-btn pm-btn-cancel"
                        onClick={onClose}
                        disabled={publishing}
                    >
                        Cancel
                    </button>
                    <button
                        ref={confirmRef}
                        className="pm-btn pm-btn-confirm"
                        onClick={onConfirm}
                        disabled={publishing}
                    >
                        {publishing
                            ? <><i className="fa fa-spinner fa-spin" /> Publishing…</>
                            : <><i className="fa fa-paper-plane" /> Publish</>
                        }
                    </button>
                </div>
            </div>
        </div>
    )
}
