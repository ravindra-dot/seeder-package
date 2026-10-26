<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Laravel Model Integrity Toolkit — Usage &amp; Rules</title>
<meta name="description" content="Static diagnostics for Laravel Eloquent model and factory mappings. Install, run, and understand the findings.">
<style>
    :root {
        --bg: #070b16;
        --bg-soft: #0b1222;
        --panel: rgba(16, 25, 44, 0.78);
        --panel-solid: #101a2e;
        --border: rgba(148, 163, 184, 0.16);
        --border-strong: rgba(148, 163, 184, 0.28);
        --text: #e8eefb;
        --muted: #96a4ba;
        --muted-strong: #b8c4d8;
        --accent: #5eead4;
        --accent-2: #818cf8;
        --danger: #fb7185;
        --warning: #fbbf24;
        --success: #34d399;
        --radius-lg: 22px;
        --radius-md: 14px;
        --radius-sm: 9px;
        --shadow: 0 30px 80px -40px rgba(45, 212, 191, 0.35);
        --font-sans: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        --font-mono: ui-monospace, SFMono-Regular, "Cascadia Code", Menlo, Consolas, "Liberation Mono", monospace;
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
        -webkit-text-size-adjust: 100%;
    }

    body {
        margin: 0;
        min-width: 280px;
        background: var(--bg);
        color: var(--text);
        font-family: var(--font-sans);
        line-height: 1.65;
        -webkit-font-smoothing: antialiased;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        z-index: -2;
        background:
            radial-gradient(55rem 38rem at 12% -8%, rgba(45, 212, 191, 0.18), transparent 65%),
            radial-gradient(48rem 34rem at 92% 4%, rgba(129, 140, 248, 0.2), transparent 62%),
            radial-gradient(60rem 42rem at 50% 110%, rgba(14, 116, 144, 0.16), transparent 65%);
        pointer-events: none;
    }

    body::after {
        content: "";
        position: fixed;
        inset: 0;
        z-index: -1;
        background-image:
            linear-gradient(rgba(148, 163, 184, 0.045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(148, 163, 184, 0.045) 1px, transparent 1px);
        background-size: 64px 64px;
        mask-image: radial-gradient(ellipse at 50% 0%, #000 0%, transparent 72%);
        -webkit-mask-image: radial-gradient(ellipse at 50% 0%, #000 0%, transparent 72%);
        pointer-events: none;
    }

    ::selection {
        background: rgba(94, 234, 212, 0.28);
        color: #fff;
    }

    a {
        color: var(--accent);
        text-decoration-color: rgba(94, 234, 212, 0.35);
        text-underline-offset: 3px;
    }

    a:hover {
        text-decoration-color: var(--accent);
    }

    :focus-visible {
        outline: 2px solid var(--accent);
        outline-offset: 3px;
        border-radius: 4px;
    }

    .container {
        width: min(1120px, 100% - clamp(2rem, 6vw, 5rem));
        margin-inline: auto;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .skip-link {
        position: fixed;
        top: 0.75rem;
        left: 0.75rem;
        z-index: 100;
        padding: 0.65rem 1rem;
        border-radius: var(--radius-sm);
        background: var(--accent);
        color: #06201c;
        font-weight: 700;
        transform: translateY(-180%);
        transition: transform 160ms ease;
    }

    .skip-link:focus {
        transform: translateY(0);
    }

    /* Header */
    .site-header {
        position: sticky;
        top: 0;
        z-index: 50;
        border-bottom: 1px solid var(--border);
        background: rgba(7, 11, 22, 0.72);
        backdrop-filter: blur(18px) saturate(140%);
        -webkit-backdrop-filter: blur(18px) saturate(140%);
    }

    .header-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        min-height: 4.25rem;
    }

    .brand {
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
        color: var(--text);
        font-weight: 750;
        letter-spacing: -0.02em;
        text-decoration: none;
        white-space: nowrap;
    }

    .brand-mark {
        display: grid;
        place-items: center;
        width: 2.15rem;
        height: 2.15rem;
        border-radius: 11px;
        background: linear-gradient(140deg, rgba(94, 234, 212, 0.2), rgba(129, 140, 248, 0.26));
        border: 1px solid rgba(94, 234, 212, 0.3);
        color: var(--accent);
    }

    .brand-mark svg {
        width: 1.2rem;
        height: 1.2rem;
    }

    .brand-accent {
        color: var(--accent);
    }

    .site-nav {
        display: flex;
        align-items: center;
        gap: 0.2rem;
    }

    .site-nav a {
        display: inline-flex;
        align-items: center;
        min-height: 2.5rem;
        padding: 0.35rem 0.7rem;
        border-radius: var(--radius-sm);
        color: var(--muted-strong);
        font-size: 0.92rem;
        font-weight: 550;
        text-decoration: none;
        transition: color 150ms ease, background 150ms ease;
    }

    .site-nav a:hover {
        color: var(--text);
        background: rgba(148, 163, 184, 0.1);
    }

    .site-nav .nav-cta {
        margin-left: 0.45rem;
        padding-inline: 0.95rem;
        color: #06121a;
        background: linear-gradient(135deg, #2dd4bf, #a5b4fc);
        font-weight: 750;
    }

    .site-nav .nav-cta:hover {
        color: #06121a;
        background: linear-gradient(135deg, #5eead4, #c7d2fe);
    }

    .nav-toggle {
        display: none;
        align-items: center;
        justify-content: center;
        width: 2.75rem;
        height: 2.75rem;
        border: 1px solid var(--border-strong);
        border-radius: 10px;
        background: rgba(148, 163, 184, 0.08);
        color: var(--text);
        cursor: pointer;
    }

    .nav-toggle svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    /* Hero */
    .hero {
        padding-block: clamp(3.25rem, 8vw, 6.5rem) clamp(2.5rem, 6vw, 4.5rem);
        overflow: hidden;
    }

    .hero-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
        gap: clamp(2.25rem, 5vw, 4.5rem);
        align-items: center;
    }

    .eyebrow {
        display: inline-flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.55rem;
        margin: 0 0 1.35rem;
        padding: 0.4rem 0.8rem;
        border: 1px solid rgba(94, 234, 212, 0.26);
        border-radius: 999px;
        background: rgba(94, 234, 212, 0.07);
        color: #bff8ee;
        font-size: 0.78rem;
        font-weight: 650;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .eyebrow .dot {
        width: 0.45rem;
        height: 0.45rem;
        border-radius: 50%;
        background: var(--accent);
        box-shadow: 0 0 0 4px rgba(94, 234, 212, 0.15);
    }

    h1 {
        margin: 0;
        font-size: clamp(2.35rem, 5.4vw, 4.1rem);
        line-height: 1.04;
        letter-spacing: -0.045em;
        text-wrap: balance;
    }

    .grad {
        color: #7dd3fc;
    }

    .lede {
        max-width: 34rem;
        margin: 1.4rem 0 0;
        color: var(--muted-strong);
        font-size: clamp(1.02rem, 1.6vw, 1.13rem);
    }

    .actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 1.9rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        min-height: 3.05rem;
        padding: 0.7rem 1.25rem;
        border-radius: 12px;
        font-size: 0.98rem;
        font-weight: 750;
        text-decoration: none;
        transition: transform 160ms ease, box-shadow 160ms ease, background 160ms ease, border-color 160ms ease;
    }

    .btn svg {
        width: 1.05rem;
        height: 1.05rem;
    }

    .btn-primary {
        color: #06121a;
        background: linear-gradient(135deg, #2dd4bf, #a5b4fc);
        box-shadow: 0 16px 38px -18px rgba(45, 212, 191, 0.75);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 22px 44px -18px rgba(45, 212, 191, 0.9);
    }

    .btn-ghost {
        border: 1px solid var(--border-strong);
        background: rgba(148, 163, 184, 0.06);
        color: var(--text);
    }

    .btn-ghost:hover {
        border-color: rgba(94, 234, 212, 0.45);
        background: rgba(94, 234, 212, 0.08);
    }

    .hero-points {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem 1.15rem;
        margin: 1.6rem 0 0;
        padding: 0;
        list-style: none;
        color: var(--muted);
        font-size: 0.9rem;
    }

    .hero-points li {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .hero-points svg {
        width: 1rem;
        height: 1rem;
        color: var(--success);
        flex: 0 0 auto;
    }

    /* Terminal */
    .terminal {
        position: relative;
        border: 1px solid var(--border-strong);
        border-radius: var(--radius-lg);
        background: linear-gradient(160deg, rgba(18, 30, 52, 0.98), rgba(8, 14, 28, 0.98));
        box-shadow: var(--shadow), 0 1px 0 rgba(255, 255, 255, 0.05) inset;
        overflow: hidden;
    }

    .terminal::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(140deg, rgba(94, 234, 212, 0.45), transparent 40%, rgba(129, 140, 248, 0.4));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    .terminal-bar {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.8rem 1rem;
        border-bottom: 1px solid var(--border);
        background: rgba(255, 255, 255, 0.025);
    }

    .terminal-dots {
        display: flex;
        gap: 0.35rem;
    }

    .terminal-dots span {
        width: 0.62rem;
        height: 0.62rem;
        border-radius: 50%;
        background: rgba(148, 163, 184, 0.32);
    }

    .terminal-dots span:first-child { background: #fb7185; }
    .terminal-dots span:nth-child(2) { background: #fbbf24; }
    .terminal-dots span:last-child { background: #34d399; }

    .terminal-title {
        margin-left: 0.35rem;
        color: var(--muted);
        font-family: var(--font-mono);
        font-size: 0.72rem;
    }

    .terminal-body {
        padding: 1.15rem 1.2rem 1.3rem;
        font-family: var(--font-mono);
        font-size: clamp(0.74rem, 1.5vw, 0.82rem);
        line-height: 1.75;
        overflow-x: auto;
    }

    .terminal-body p {
        margin: 0;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .terminal-body .prompt {
        color: var(--accent);
        user-select: none;
    }

    .terminal-body .out-error {
        margin-top: 0.85rem;
        color: var(--danger);
        font-weight: 700;
    }

    .terminal-body .out-warn {
        margin-top: 0.85rem;
        color: var(--warning);
        font-weight: 700;
    }

    .terminal-body .out-dim {
        color: var(--muted);
    }

    .terminal-body .out {
        color: var(--muted-strong);
        white-space: normal;
    }

    .terminal-body .hl {
        color: #fda4af;
    }

    .terminal-body .out-result {
        margin-top: 0.6rem;
        color: var(--text);
        font-weight: 700;
    }

    .terminal-body .out-ok {
        color: var(--success);
        font-weight: 700;
    }

    /* Stats */
    .stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.75rem;
        padding-block: 0.4rem 0.6rem;
    }

    .stat {
        padding: 1.1rem 1.25rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        background: rgba(16, 25, 44, 0.55);
    }

    .stat strong {
        display: block;
        font-size: clamp(1.45rem, 2.6vw, 1.85rem);
        line-height: 1.15;
        letter-spacing: -0.03em;
        color: #d7fef8;
    }

    .stat span {
        color: var(--muted);
        font-size: 0.82rem;
        font-weight: 550;
    }

    /* Sections */
    section {
        scroll-margin-top: 5.5rem;
    }

    .section {
        padding-block: clamp(3.5rem, 7vw, 5.75rem);
    }

    .section-kicker {
        margin: 0 0 0.7rem;
        color: var(--accent);
        font-size: 0.78rem;
        font-weight: 750;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .section-title {
        max-width: 46rem;
        margin: 0;
        font-size: clamp(1.7rem, 3.4vw, 2.55rem);
        line-height: 1.14;
        letter-spacing: -0.035em;
        text-wrap: balance;
    }

    .section-sub {
        max-width: 44rem;
        margin: 0.9rem 0 0;
        color: var(--muted-strong);
        font-size: clamp(0.98rem, 1.4vw, 1.06rem);
    }

    .section-head {
        margin-bottom: clamp(1.75rem, 4vw, 2.6rem);
    }

    /* Overview pipeline */
    .overview-grid {
        display: grid;
        grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.05fr);
        gap: clamp(2rem, 5vw, 4rem);
        align-items: start;
    }

    .prose p {
        margin: 0 0 1rem;
        color: var(--muted-strong);
    }

    .prose p:last-child {
        margin-bottom: 0;
    }

    .pipeline {
        display: grid;
        gap: 0.75rem;
        margin: 0;
        padding: 0;
        list-style: none;
        counter-reset: step;
    }

    .pipeline li {
        position: relative;
        padding: 1.1rem 1.2rem 1.1rem 4rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        background: var(--panel);
    }

    .pipeline li::before {
        content: "0" counter(step);
        counter-increment: step;
        position: absolute;
        top: 1.05rem;
        left: 1.15rem;
        color: rgba(94, 234, 212, 0.75);
        font-family: var(--font-mono);
        font-size: 0.82rem;
        font-weight: 700;
    }

    .pipeline strong {
        display: block;
        margin-bottom: 0.15rem;
        font-size: 0.98rem;
    }

    .pipeline span {
        color: var(--muted);
        font-size: 0.9rem;
    }

    /* Code cards */
    .steps {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        align-items: stretch;
    }

    .step-card {
        display: flex;
        flex-direction: column;
        padding: 1.35rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        background: linear-gradient(165deg, rgba(18, 29, 50, 0.88), rgba(11, 18, 34, 0.78));
    }

    .step-label {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        margin-bottom: 0.7rem;
        color: var(--accent);
        font-family: var(--font-mono);
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .step-label span {
        display: grid;
        place-items: center;
        width: 1.55rem;
        height: 1.55rem;
        border-radius: 7px;
        background: rgba(94, 234, 212, 0.12);
        border: 1px solid rgba(94, 234, 212, 0.24);
        color: #d7fef8;
        font-size: 0.72rem;
        letter-spacing: 0;
    }

    .step-card h3 {
        margin: 0 0 0.4rem;
        font-size: 1.05rem;
        letter-spacing: -0.015em;
    }

    .step-card p {
        margin: 0 0 1rem;
        color: var(--muted);
        font-size: 0.9rem;
    }

    .step-card .code-card {
        margin-top: auto;
    }

    .code-card {
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        background: #070d1a;
        overflow: hidden;
    }

    .code-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.45rem 0.55rem 0.45rem 0.85rem;
        border-bottom: 1px solid var(--border);
        background: rgba(148, 163, 184, 0.05);
    }

    .code-lang {
        color: var(--muted);
        font-family: var(--font-mono);
        font-size: 0.68rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .copy-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.32rem;
        min-height: 2rem;
        padding: 0.25rem 0.6rem;
        border: 1px solid var(--border);
        border-radius: 7px;
        background: rgba(148, 163, 184, 0.08);
        color: var(--muted-strong);
        font-family: var(--font-sans);
        font-size: 0.72rem;
        font-weight: 650;
        cursor: pointer;
        transition: color 150ms ease, border-color 150ms ease, background 150ms ease;
    }

    .copy-btn:hover {
        color: var(--text);
        border-color: rgba(94, 234, 212, 0.4);
        background: rgba(94, 234, 212, 0.1);
    }

    .copy-btn svg {
        width: 0.85rem;
        height: 0.85rem;
    }

    .code-card pre {
        margin: 0;
        padding: 0.95rem 1rem;
        overflow-x: auto;
        color: #d7e3f4;
        font-family: var(--font-mono);
        font-size: 0.8rem;
        line-height: 1.7;
        tab-size: 4;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .code-card code {
        font-family: inherit;
    }

    .note {
        display: flex;
        gap: 0.7rem;
        align-items: flex-start;
        margin-top: 1rem;
        padding: 0.9rem 1rem;
        border: 1px solid rgba(94, 234, 212, 0.2);
        border-radius: var(--radius-md);
        background: rgba(94, 234, 212, 0.06);
        color: #c7f9f0;
        font-size: 0.87rem;
    }

    .note svg {
        width: 1.05rem;
        height: 1.05rem;
        margin-top: 0.16rem;
        flex: 0 0 auto;
        color: var(--accent);
    }

    .note p {
        margin: 0;
    }

    /* Usage */
    .usage-grid {
        display: grid;
        grid-template-columns: minmax(0, 0.92fr) minmax(0, 1.08fr);
        gap: clamp(1.5rem, 4vw, 2.75rem);
        align-items: start;
    }

    .usage-copy h3,
    .rules h3 {
        margin: 0 0 0.5rem;
        font-size: 1.08rem;
    }

    .usage-copy p {
        margin: 0 0 1.1rem;
        color: var(--muted-strong);
        font-size: 0.95rem;
    }

    .checklist {
        display: grid;
        gap: 0.55rem;
        margin: 0 0 1.25rem;
        padding: 0;
        list-style: none;
    }

    .checklist li {
        display: flex;
        gap: 0.55rem;
        align-items: flex-start;
        color: var(--muted-strong);
        font-size: 0.92rem;
    }

    .checklist svg {
        width: 1rem;
        height: 1rem;
        margin-top: 0.22rem;
        flex: 0 0 auto;
        color: var(--accent);
    }

    .exit-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
        font-size: 0.88rem;
    }

    .exit-table th,
    .exit-table td {
        padding: 0.65rem 0.8rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
        vertical-align: top;
    }

    .exit-table th {
        background: rgba(148, 163, 184, 0.06);
        color: var(--muted);
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .exit-table tr:last-child td {
        border-bottom: 0;
    }

    .exit-table td:first-child {
        width: 5.5rem;
        color: var(--text);
        font-family: var(--font-mono);
        font-weight: 700;
    }

    .exit-table td {
        color: var(--muted-strong);
    }

    /* Rules */
    .rules-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .rule-card {
        display: flex;
        flex-direction: column;
        padding: 1.35rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        background: var(--panel);
    }

    .rule-card header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .rule-id {
        color: #c7d2fe;
        font-family: var(--font-mono);
        font-size: 0.78rem;
        word-break: break-word;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.18rem 0.52rem;
        border-radius: 999px;
        font-size: 0.68rem;
        font-weight: 750;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .badge::before {
        content: "";
        width: 0.38rem;
        height: 0.38rem;
        border-radius: 50%;
        background: currentColor;
    }

    .badge-error {
        background: rgba(251, 113, 133, 0.12);
        border: 1px solid rgba(251, 113, 133, 0.28);
        color: #fda4af;
    }

    .badge-warning {
        background: rgba(251, 191, 36, 0.1);
        border: 1px solid rgba(251, 191, 36, 0.26);
        color: #fcd34d;
    }

    .rule-card p {
        margin: 0;
        color: var(--muted-strong);
        font-size: 0.91rem;
    }

    .rule-card footer {
        margin-top: 0.9rem;
        padding-top: 0.8rem;
        border-top: 1px solid var(--border);
        color: var(--muted);
        font-size: 0.82rem;
    }

    /* Scope */
    .scope-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .scope-card {
        padding: clamp(1.3rem, 3vw, 1.75rem);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        background: var(--panel);
    }

    .scope-card h3 {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        margin: 0 0 1rem;
        font-size: 1.02rem;
    }

    .scope-card h3 svg {
        width: 1.15rem;
        height: 1.15rem;
        flex: 0 0 auto;
    }

    .scope-card.now h3 svg { color: var(--success); }
    .scope-card.later h3 svg { color: var(--muted); }

    .scope-list {
        display: grid;
        gap: 0.55rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .scope-list li {
        position: relative;
        padding-left: 1.3rem;
        color: var(--muted-strong);
        font-size: 0.9rem;
    }

    .scope-list li::before {
        position: absolute;
        left: 0;
        top: 0;
        font-family: var(--font-mono);
        font-weight: 700;
    }

    .scope-card.now .scope-list li::before {
        content: "✓";
        color: var(--success);
    }

    .scope-card.later .scope-list li::before {
        content: "–";
        color: var(--muted);
    }

    /* Safety */
    .safety-panel {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: clamp(1.5rem, 4vw, 3rem);
        padding: clamp(1.5rem, 4vw, 2.5rem);
        border: 1px solid rgba(94, 234, 212, 0.22);
        border-radius: var(--radius-lg);
        background:
            radial-gradient(30rem 18rem at 0% 0%, rgba(45, 212, 191, 0.12), transparent 70%),
            rgba(13, 22, 40, 0.88);
    }

    .safety-panel h2 {
        margin: 0 0 0.8rem;
        font-size: clamp(1.35rem, 2.6vw, 1.75rem);
        letter-spacing: -0.025em;
    }

    .safety-panel p {
        margin: 0 0 0.85rem;
        color: var(--muted-strong);
        font-size: 0.94rem;
    }

    .safety-panel p:last-child {
        margin-bottom: 0;
    }

    .safety-points {
        display: grid;
        gap: 0.6rem;
        align-content: start;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .safety-points li {
        display: flex;
        gap: 0.65rem;
        align-items: flex-start;
        padding: 0.7rem 0.85rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background: rgba(7, 13, 26, 0.45);
        color: var(--muted-strong);
        font-size: 0.87rem;
    }

    .safety-points svg {
        width: 1rem;
        height: 1rem;
        margin-top: 0.18rem;
        flex: 0 0 auto;
        color: var(--accent);
    }

    /* Develop */
    .develop-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .develop-card {
        padding: clamp(1.25rem, 3vw, 1.6rem);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        background: var(--panel);
    }

    .develop-card h3 {
        margin: 0 0 0.4rem;
        font-size: 1rem;
    }

    .develop-card p {
        margin: 0 0 1rem;
        color: var(--muted);
        font-size: 0.88rem;
    }

    /* Footer */
    .site-footer {
        margin-top: 1rem;
        border-top: 1px solid var(--border);
        background: rgba(6, 10, 20, 0.6);
    }

    .footer-inner {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
        gap: 1.5rem;
        padding-block: 2.25rem;
        align-items: start;
    }

    .footer-brand {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        margin-bottom: 0.55rem;
        font-weight: 700;
    }

    .footer-brand .brand-mark {
        width: 1.8rem;
        height: 1.8rem;
        border-radius: 9px;
    }

    .footer-brand .brand-mark svg {
        width: 1rem;
        height: 1rem;
    }

    .footer-copy p {
        margin: 0;
        max-width: 30rem;
        color: var(--muted);
        font-size: 0.86rem;
    }

    .footer-links {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.5rem 1.2rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .footer-links a {
        color: var(--muted-strong);
        font-size: 0.86rem;
        text-decoration: none;
    }

    .footer-links a:hover {
        color: var(--accent);
    }

    /* Responsive */
    @media (max-width: 980px) {
        .hero-grid,
        .overview-grid,
        .usage-grid,
        .safety-panel {
            grid-template-columns: minmax(0, 1fr);
        }

        .hero-visual {
            max-width: 40rem;
        }

        .steps,
        .rules-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .steps .step-card:last-child,
        .rules-grid .rule-card:last-child {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 860px) {
        .nav-toggle {
            display: inline-flex;
        }

        .site-nav {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            flex-direction: column;
            align-items: stretch;
            gap: 0.25rem;
            padding: 0.8rem clamp(1rem, 4vw, 2rem) 1rem;
            border-bottom: 1px solid var(--border-strong);
            background: rgba(7, 11, 22, 0.98);
            box-shadow: 0 24px 40px -24px rgba(0, 0, 0, 0.9);
        }

        .site-nav.is-open {
            display: flex;
        }

        .site-nav a {
            justify-content: space-between;
            min-height: 2.9rem;
            padding-inline: 0.8rem;
            font-size: 0.98rem;
        }

        .site-nav .nav-cta {
            margin: 0.35rem 0 0;
            justify-content: center;
        }
    }

    @media (max-width: 720px) {
        .stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .steps,
        .rules-grid,
        .scope-grid,
        .develop-grid,
        .footer-inner {
            grid-template-columns: minmax(0, 1fr);
        }

        .steps .step-card:last-child,
        .rules-grid .rule-card:last-child {
            grid-column: auto;
        }

        .footer-links {
            justify-content: flex-start;
        }

        .terminal-body p {
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .terminal-body p + p {
            margin-top: 0.35rem;
        }

        .exit-table {
            display: block;
            overflow-x: auto;
        }

        .exit-table thead {
            display: none;
        }

        .exit-table tbody,
        .exit-table tr,
        .exit-table td {
            display: block;
            width: 100%;
        }

        .exit-table tr {
            padding: 0.7rem 0.85rem;
            border-bottom: 1px solid var(--border);
        }

        .exit-table tr:last-child {
            border-bottom: 0;
        }

        .exit-table td {
            padding: 0.1rem 0;
            border: 0;
        }

        .exit-table td:first-child {
            width: auto;
        }
    }

    @media (max-width: 460px) {
        .actions .btn {
            width: 100%;
        }

        .hero-points {
            display: grid;
            gap: 0.45rem;
        }

        .stat {
            padding: 0.95rem 1rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        html {
            scroll-behavior: auto;
        }

        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="#top" aria-label="Laravel Model Integrity Toolkit — back to top">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3 5 6v5c0 4.4 2.9 8.4 7 9.8 4.1-1.4 7-5.4 7-9.8V6l-7-3Z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </span>
            <span>Model<span class="brand-accent">Integrity</span></span>
        </a>

        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <path d="M4 7h16M4 12h16M4 17h16"/>
            </svg>
            <span class="sr-only">Toggle navigation</span>
        </button>

        <nav class="site-nav" id="site-nav" aria-label="Documentation sections">
            <a href="#overview">Overview</a>
            <a href="#install">Install</a>
            <a href="#usage">Usage</a>
            <a href="#rules">Rules</a>
            <a href="#scope">Scope</a>
            <a href="#safety">Safety</a>
            <a class="nav-cta" href="#install">Get started</a>
        </nav>
    </div>
</header>

<main id="main">
    <section class="hero" id="top">
        <div class="container hero-grid">
            <div class="hero-copy">
                <p class="eyebrow"><span class="dot" aria-hidden="true"></span> Laravel 12 · PHP 8.2+ · dev dependency</p>
                <h1>Catch broken <span class="grad">model → factory</span> mappings before runtime.</h1>
                <p class="lede">
                    A static analyzer for Laravel applications. It reads your PHP source with an AST parser and reports
                    factories that point at missing models, plus conventional mappings that look suspicious — with the
                    exact file, line, and a suggested fix.
                </p>
                <div class="actions">
                    <a class="btn btn-primary" href="#install">
                        Install the package
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14m-6-6 6 6-6 6"/>
                        </svg>
                    </a>
                    <a class="btn btn-ghost" href="#rules">Browse the rules</a>
                </div>
                <ul class="hero-points">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                        Static AST analysis
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                        No database writes
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                        CI-friendly exit codes
                    </li>
                </ul>
            </div>

            <div class="hero-visual">
                <div class="terminal" role="img" aria-label="Example terminal session showing the check command and one error finding">
                    <div class="terminal-bar">
                        <span class="terminal-dots" aria-hidden="true"><span></span><span></span><span></span></span>
                        <span class="terminal-title">consumer-app — zsh</span>
                    </div>
                    <div class="terminal-body">
                        <p><span class="prompt" aria-hidden="true">$ </span>composer require --dev model-integrity/laravel-model-integrity:*</p>
                        <p><span class="prompt" aria-hidden="true">$ </span>php artisan model-integrity:check</p>
                        <p class="out-error">ERROR factory.model_not_found</p>
                        <p class="out-dim">database/factories/UserFactory.php:10</p>
                        <p class="out">Factory Database\Factories\UserFactory targets missing model <span class="hl">App\Models\Missing</span>.</p>
                        <p class="out-dim">Suggestion: Correct the import or $model reference, or add the model class under app/.</p>
                        <p class="out-result">Result: 1 finding(s)</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container stats" aria-label="Project at a glance">
        <div class="stat"><strong>3</strong><span>rules available today</span></div>
        <div class="stat"><strong>0</strong><span>database writes</span></div>
        <div class="stat"><strong>0 / 1</strong><span>stable exit codes</span></div>
        <div class="stat"><strong>AST</strong><span>parsed, never executed</span></div>
    </div>

    <section class="section" id="overview">
        <div class="container">
            <div class="section-head">
                <p class="section-kicker">Why it exists</p>
                <h2 class="section-title">Laravel conventions are convenient — until a name or import drifts.</h2>
                <p class="section-sub">
                    Models, factories, namespaces, and relationships are wired together by convention. A wrong import
                    or an unexpected explicit target can stay hidden until code calls the factory. This tool surfaces
                    those mismatches while you work locally or review a change.
                </p>
            </div>

            <div class="overview-grid">
                <div class="prose">
                    <p>
                        The analyzer scans <code>app/</code> for class declarations and
                        <code>database/factories/</code> for direct <code>Factory</code> subclasses. It resolves
                        namespace imports and fully qualified class names, then compares what each factory declares
                        against the classes it discovered.
                    </p>
                    <p>
                        It understands Laravel's default <code>Database\Factories\...Factory</code> convention,
                        including plural and irregular model names. Models may use <code>HasFactory</code> directly
                        or inherit it from an application base class.
                    </p>
                    <p>
                        Explicit overrides are respected: <code>UseFactory</code> on the model, or a
                        <code>$factory</code> property or <code>newFactory()</code> method on the model or an
                        application ancestor, suppress the convention warning.
                    </p>
                </div>

                <ol class="pipeline">
                    <li>
                        <strong>Parse</strong>
                        <span>PHP source is read with <code>nikic/php-parser</code>. Files are parsed, never included.</span>
                    </li>
                    <li>
                        <strong>Resolve</strong>
                        <span>Namespaces, imports, inheritance, and factories are indexed into a lightweight project map.</span>
                    </li>
                    <li>
                        <strong>Report</strong>
                        <span>Findings carry a stable rule ID, severity, file, line, and a practical suggestion.</span>
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section" id="install">
        <div class="container">
            <div class="section-head">
                <p class="section-kicker">Installation</p>
                <h2 class="section-title">Three commands from checkout to first report.</h2>
                <p class="section-sub">
                    The package is installed from a local checkout with a Composer path repository. Run these commands
                    inside your Laravel application, not inside this package directory.
                </p>
            </div>

            <div class="steps">
                <article class="step-card">
                    <span class="step-label"><span>1</span> Add the path</span>
                    <h3>Register the repository</h3>
                    <p>Point Composer at this package checkout. Replace the example path with its absolute location.</p>
                    <div class="code-card">
                        <div class="code-head">
                            <span class="code-lang">bash</span>
                            <button class="copy-btn" type="button" data-copy="code-install-1">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1"/></svg>
                                <span>Copy</span>
                            </button>
                        </div>
                        <pre id="code-install-1"><code>composer config repositories.model-integrity \
  path "C:/path/to/seeder-package"</code></pre>
                    </div>
                </article>

                <article class="step-card">
                    <span class="step-label"><span>2</span> Require it</span>
                    <h3>Install as a dev dependency</h3>
                    <p>The package name is provisional. Laravel package discovery registers the Artisan command automatically.</p>
                    <div class="code-card">
                        <div class="code-head">
                            <span class="code-lang">bash</span>
                            <button class="copy-btn" type="button" data-copy="code-install-2">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1"/></svg>
                                <span>Copy</span>
                            </button>
                        </div>
                        <pre id="code-install-2"><code>composer require --dev \
  model-integrity/laravel-model-integrity:*</code></pre>
                    </div>
                </article>

                <article class="step-card">
                    <span class="step-label"><span>3</span> Run the check</span>
                    <h3>Scan your application</h3>
                    <p>Run it from the application root. Add it to a local workflow or CI job once the baseline is clean.</p>
                    <div class="code-card">
                        <div class="code-head">
                            <span class="code-lang">bash</span>
                            <button class="copy-btn" type="button" data-copy="code-install-3">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1"/></svg>
                                <span>Copy</span>
                            </button>
                        </div>
                        <pre id="code-install-3"><code>php artisan model-integrity:check</code></pre>
                    </div>
                </article>
            </div>

            <div class="note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5m0-8h.01"/></svg>
                <p>
                    Installation in a separate Laravel 12 application and the wider PHP/Laravel compatibility matrix
                    are still being verified. Treat this as an initial local implementation.
                </p>
            </div>
        </div>
    </section>

    <section class="section" id="usage">
        <div class="container">
            <div class="section-head">
                <p class="section-kicker">Usage</p>
                <h2 class="section-title">Read the finding, fix the reference, run it again.</h2>
                <p class="section-sub">
                    Each line in the console report is designed to be actionable on its own: severity, rule ID,
                    location, message, and suggestion.
                </p>
            </div>

            <div class="usage-grid">
                <div class="usage-copy">
                    <h3>What to look at</h3>
                    <ul class="checklist">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                            <span><strong>Severity</strong> tells you whether the build should fail.</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                            <span><strong>Rule ID</strong> is stable, so it can be referenced in docs or automation.</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                            <span><strong>File and line</strong> point to the declaration to inspect.</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                            <span><strong>Suggestion</strong> names the next concrete step.</span>
                        </li>
                    </ul>
                    <p>
                        For <code>factory.model_not_found</code>, check the factory's <code>$model</code> import or
                        class reference. For <code>factory.model_mapping_suspicious</code>, decide whether the
                        conventionally named factory should target that model or whether the model needs an explicit
                        factory override.
                    </p>

                    <h3 style="margin-top: 1.5rem;">Exit codes</h3>
                    <table class="exit-table">
                        <thead>
                            <tr><th scope="col">Code</th><th scope="col">Meaning</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>0</td><td>No error findings. Warnings alone still exit <code>0</code>.</td></tr>
                            <tr><td>1</td><td>At least one finding has <code>error</code> severity.</td></tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    <div class="code-card">
                        <div class="code-head">
                            <span class="code-lang">console output</span>
                            <button class="copy-btn" type="button" data-copy="code-sample-output">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1"/></svg>
                                <span>Copy</span>
                            </button>
                        </div>
                        <pre id="code-sample-output"><code><span style="color:#fb7185;font-weight:700;">ERROR</span> factory.model_not_found database/factories/UserFactory.php:10 Factory Database\Factories\UserFactory targets missing model App\Models\Missing. Suggestion: Correct the import or $model reference, or add the model class under app/.
<span style="color:#e8eefb;font-weight:700;">Result: 1 finding(s)</span></code></pre>
                    </div>

                    <div class="note" style="margin-top: 1rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 5 6v5c0 4.4 2.9 8.4 7 9.8 4.1-1.4 7-5.4 7-9.8V6l-7-3Z"/><path d="m9 12 2 2 4-4"/></svg>
                        <p>
                            A warning-only run is not a failure. Warnings mean “review this”; errors mean “this
                            reference is unresolved.”
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="rules">
        <div class="container">
            <div class="section-head">
                <p class="section-kicker">Rule catalog</p>
                <h2 class="section-title">What the current version checks.</h2>
                <p class="section-sub">
                    Three rules are implemented and tested. A model without a factory is intentionally not reported
                    as an error — many models do not need one.
                </p>
            </div>

            <div class="rules-grid">
                <article class="rule-card">
                    <header>
                        <span class="rule-id">factory.model_not_found</span>
                        <span class="badge badge-error">Error</span>
                    </header>
                    <p>
                        A factory's explicit <code>$model</code> target points at an <code>App\...</code> class that
                        was not discovered under <code>app/</code>.
                    </p>
                    <footer>Check the import, namespace, and class name — or add the missing model.</footer>
                </article>

                <article class="rule-card">
                    <header>
                        <span class="rule-id">factory.model_mapping_suspicious</span>
                        <span class="badge badge-warning">Warning</span>
                    </header>
                    <p>
                        A model conventionally resolves to a factory, but that factory explicitly targets a different
                        discovered model.
                    </p>
                    <footer>Correct the factory target, or configure the model factory explicitly.</footer>
                </article>

                <article class="rule-card">
                    <header>
                        <span class="rule-id">analysis.parse_error</span>
                        <span class="badge badge-warning">Warning</span>
                    </header>
                    <p>
                        A PHP file could not be parsed. The file is skipped and a warning is reported with its location.
                    </p>
                    <footer>Fix the syntax and run the check again.</footer>
                </article>
            </div>
        </div>
    </section>

    <section class="section" id="scope">
        <div class="container">
            <div class="section-head">
                <p class="section-kicker">Scope</p>
                <h2 class="section-title">Honest coverage, documented limits.</h2>
                <p class="section-sub">
                    Static analysis can only verify what it can resolve from source. An empty report means the
                    current rules found nothing — not that every mapping is guaranteed to work at runtime.
                </p>
            </div>

            <div class="scope-grid">
                <article class="scope-card now">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.4 2.4 4.6-5"/></svg>
                        Works today
                    </h3>
                    <ul class="scope-list">
                        <li>Namespace and import resolution to fully qualified names.</li>
                        <li>Direct and inherited <code>HasFactory</code> detection.</li>
                        <li>Explicit <code>App\...</code> model reference checks.</li>
                        <li>Conventional factory candidate resolution, including plural names.</li>
                        <li><code>UseFactory</code> and inherited <code>$factory</code>/<code>newFactory()</code> overrides.</li>
                        <li>Safe parse-error reporting with file and line.</li>
                    </ul>
                </article>

                <article class="scope-card later">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12h8"/></svg>
                        Not analyzed yet
                    </h3>
                    <ul class="scope-list">
                        <li>Seeder references and dependency order.</li>
                        <li>Relationship methods and target models.</li>
                        <li>Absent factories (intentionally not treated as errors).</li>
                        <li><code>HasFactory</code> supplied by a custom trait.</li>
                        <li>Inheritance chains that leave <code>app/</code> before reaching <code>Model</code>.</li>
                        <li>Custom model roots, factory name resolvers, dynamic targets.</li>
                        <li>JSON output, configuration, and severity thresholds.</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section class="section" id="safety">
        <div class="container">
            <div class="safety-panel">
                <div>
                    <p class="section-kicker" style="margin-bottom: 0.6rem;">Safety</p>
                    <h2>The analyzer reads source. It does not run your app's data layer.</h2>
                    <p>
                        No migrations, seeders, or Eloquent queries are executed. The analyzer does not connect to,
                        write to, truncate, or delete application database data.
                    </p>
                    <p>
                        <strong>One caveat:</strong> running the Artisan command still boots the host Laravel
                        application and its service providers. Use a disposable environment if application boot has
                        side effects.
                    </p>
                </div>
                <ul class="safety-points">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                        <span>Files are parsed, never <code>include</code>d or executed.</span>
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="6" rx="7" ry="3"/><path d="M5 6v6c0 1.7 3.1 3 7 3s7-1.3 7-3V6M5 12v6c0 1.7 3.1 3 7 3s7-1.3 7-3v-6"/></svg>
                        <span>No database connection or mutation.</span>
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16M4 12h10M4 19h7"/></svg>
                        <span>Normal application files are not modified.</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="section" id="develop">
        <div class="container">
            <div class="section-head">
                <p class="section-kicker">Development</p>
                <h2 class="section-title">Work on the package itself.</h2>
                <p class="section-sub">
                    The test suite uses source fixtures and does not require a database. Tests, formatting, and static
                    analysis are the project's quality gates.
                </p>
            </div>

            <div class="develop-grid">
                <article class="develop-card">
                    <h3>Install dependencies</h3>
                    <p>From this package directory.</p>
                    <div class="code-card">
                        <div class="code-head">
                            <span class="code-lang">bash</span>
                            <button class="copy-btn" type="button" data-copy="code-dev-1">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1"/></svg>
                                <span>Copy</span>
                            </button>
                        </div>
                        <pre id="code-dev-1"><code>composer install</code></pre>
                    </div>
                </article>

                <article class="develop-card">
                    <h3>Run the quality gates</h3>
                    <p>PHPUnit, Pint, and PHPStan.</p>
                    <div class="code-card">
                        <div class="code-head">
                            <span class="code-lang">bash</span>
                            <button class="copy-btn" type="button" data-copy="code-dev-2">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1"/></svg>
                                <span>Copy</span>
                            </button>
                        </div>
                        <pre id="code-dev-2"><code>composer test
composer format:test
composer analyse</code></pre>
                    </div>
                </article>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container footer-inner">
        <div class="footer-copy">
            <div class="footer-brand">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3 5 6v5c0 4.4 2.9 8.4 7 9.8 4.1-1.4 7-5.4 7-9.8V6l-7-3Z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </span>
                <span>Laravel Model Integrity Toolkit</span>
            </div>
            <p>
                Initial local implementation. No finding does not prove every runtime mapping is correct — static
                analysis only verifies what it can resolve from source.
            </p>
        </div>
        <ul class="footer-links">
            <li><a href="README.md">README</a></li>
            <li><a href="PROJECT_SCOPE.md">Project scope</a></li>
            <li><a href="LICENSE">License</a></li>
        </ul>
    </div>
</footer>

<script>
    (() => {
        const nav = document.getElementById('site-nav');
        const toggle = document.querySelector('.nav-toggle');

        if (nav && toggle) {
            toggle.addEventListener('click', () => {
                const open = nav.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', String(open));
            });

            nav.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    nav.classList.remove('is-open');
                    toggle.setAttribute('aria-expanded', 'false');
                });
            });
        }

        const copy = async (text) => {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
                return;
            }

            const area = document.createElement('textarea');
            area.value = text;
            area.setAttribute('readonly', '');
            area.style.position = 'fixed';
            area.style.opacity = '0';
            document.body.appendChild(area);
            area.select();
            document.execCommand('copy');
            area.remove();
        };

        document.querySelectorAll('[data-copy]').forEach((button) => {
            button.addEventListener('click', async () => {
                const target = document.getElementById(button.dataset.copy);
                if (!target) {
                    return;
                }

                const label = button.querySelector('span');
                const original = label ? label.textContent : 'Copy';

                try {
                    await copy(target.innerText.trim());
                    if (label) {
                        label.textContent = 'Copied';
                    }
                } catch {
                    if (label) {
                        label.textContent = 'Press Ctrl+C';
                    }
                }

                window.setTimeout(() => {
                    if (label) {
                        label.textContent = original;
                    }
                }, 1800);
            });
        });
    })();
</script>
</body>
</html>
