# 🗺 ARCHITECTURE.md
## NLP Labs Vietnam — Monorepo Source Architecture

> **Maintainer:** [@Dennis-nham](https://github.com/Dennis-nham) · NLP GROUP JSC  
> **Build system:** Turborepo · pnpm workspaces · Node ≥ 20  
> **Design language:** Apple HIG · `@alisababivip/*`

---

## 🏛 Organization Overview

```
╔══════════════════════════════════════════════════════════════════════╗
║           @nlp-labs-vietnam  ·  GitHub Organization                 ║
║     AI for Clean Energy · Vietnamese NLP · Financial Intelligence    ║
╚══════════════════════════════════════════════════════════════════════╝
         │
         ├── 🎨  ios-design-system        (@thekingbao)
         ├── 📈  investment-platform      (@nhamlapquoc)
         ├── 🧠  core-nlp                 (@nhamlapquoc)
         ├── 📦  wikialisa-labs           (@wikialisalabs)
         └── ⚙️  .github  [org root]     (@Dennis-nham)
```

---

## 🎨 `ios-design-system` — Pure iOS HIG Design System

> Token-driven · Zero-drift · Framework-agnostic · `@alisababivip/*`

```
📁 ios-design-system/
│
├── 📦 packages/
│   ├── 🎨 tokens/          CSS Variables + JSON Design Tokens
│   │   ├── colors.json         — iOS system palette (light / dark)
│   │   ├── typography.json     — SF Pro scale & weights
│   │   ├── spacing.json        — 4pt grid system
│   │   └── dist/ios-tokens.css — Compiled CSS custom properties
│   │
│   ├── 🖼 icons/           SVG Icons + SF Symbols + Animations
│   │   ├── svg/                — Source SVGs (outlined / filled)
│   │   ├── sprites/            — Optimized SVG sprite sheet
│   │   └── dist/               — Compiled icon font + JS tree-shaking
│   │
│   ├── 🧱 core/            Web Components (framework-agnostic CSS)
│   │   ├── src/
│   │   │   ├── button/         .ios-button
│   │   │   ├── switch/         .ios-switch (CupertinoSwitch)
│   │   │   ├── segmented/      .ios-segmented-control
│   │   │   ├── text-field/     .ios-text-field / .ios-group
│   │   │   ├── modal/          .ios-sheet (bottom sheet)
│   │   │   ├── nav-bar/        .ios-nav-bar
│   │   │   ├── tab-bar/        .ios-tab-bar
│   │   │   └── list/           .ios-list (inset grouped)
│   │   └── dist/ios-ui.css     — Single-file distribution
│   │
│   ├── ⚛️  react/           React wrapper components
│   │   ├── src/components/     — Typed React wrappers
│   │   ├── src/hooks/          — useTheme, useColorScheme
│   │   └── dist/               — ESM + CJS bundles
│   │
│   ├── 💚 vue/             Vue 3 wrapper components (Composition API)
│   │   ├── src/components/     — <IosButton />, <IosSwitch />
│   │   └── dist/               — ESM bundle
│   │
│   └── 🔪 blade/           Laravel Blade bridge
│       ├── src/                — @ios-ui Blade directives
│       └── dist/               — Compiled Blade components
│
├── 🖥 apps/
│   ├── 📖 docs/            VitePress documentation site
│   │   ├── .vitepress/config.ts
│   │   ├── components/         — Live component playground
│   │   └── guide/              — Usage guides per framework
│   │
│   └── 📱 showcase/        iOS Simulator preview (Vite)
│       ├── src/scenes/         — Per-component visual scenes
│       └── public/             — Static assets
│
├── 🔧 turbo.json           Turborepo pipeline config
├── 📋 pnpm-workspace.yaml  Workspace manifest
├── 📜 MAINTAINERS.md
└── 📝 CODEOWNERS           → @thekingbao @Dennis-nham
```

---

## 📈 `investment-platform` — AI-Driven Financial Intelligence Engine

> Apache Kafka · RabbitMQ · Fine-tuned Transformers · RAG (Milvus) · gRPC/WebSocket

```
📁 investment-platform/
│
├── 🔄 services/                  Microservices (event-driven architecture)
│   │
│   ├── 🌊 ingestion/             Apache Kafka Consumer
│   │   ├── src/consumers/        — Topic subscribers (news, social, filings)
│   │   ├── src/connectors/       — Source connectors (Reuters, Bloomberg APIs)
│   │   └── src/schemas/          — Avro / Protobuf message schemas
│   │
│   ├── 🧠 nlp-sentiment/         Sentiment Analysis Microservice
│   │   ├── src/model/            — Fine-tuned FinBERT / multilingual transformer
│   │   ├── src/inference/        — Batch + streaming inference engine
│   │   ├── src/rag/              — RAG pipeline (Milvus vector DB queries)
│   │   └── src/rl/               — Reinforcement learning feedback loop
│   │
│   ├── 🔍 nlp-ner/               Financial Named Entity Recognition
│   │   ├── src/model/            — Domain-specific NER model (company, ticker, event)
│   │   ├── src/pipeline/         — spaCy + custom transformer pipeline
│   │   └── src/dict/             — Financial lexicon & entity dictionaries
│   │
│   ├── 🐇 orchestrator/          RabbitMQ Message Broker
│   │   ├── src/queues/           — Queue definitions & routing rules
│   │   ├── src/workers/          — Task dispatchers → NLP workers
│   │   └── src/dlq/              — Dead-letter queue handlers
│   │
│   └── 🌐 api-gateway/           Client-facing API layer
│       ├── src/grpc/             — gRPC service definitions (.proto)
│       ├── src/rest/             — REST endpoints (Express / Fastify)
│       ├── src/ws/               — WebSocket streaming (real-time signals)
│       └── src/auth/             — JWT + API key middleware
│
├── 📦 packages/                  Shared libraries
│   ├── 🛠 nlp-sdk/               Client SDK — TypeScript
│   │   ├── src/client.ts         — SDK entry point
│   │   ├── src/types/            — Signal, SentimentScore, EntityResult
│   │   └── dist/                 — ESM + CJS + type declarations
│   │
│   └── 🗂 shared-types/          Shared DTOs & Protobuf definitions
│       ├── proto/                — .proto files
│       └── src/                  — Generated TypeScript types
│
├── 🖥 apps/
│   └── 📊 dashboard/             Investment Intelligence UI
│       ├── src/
│       │   ├── pages/            — Market overview, alerts, portfolio
│       │   ├── components/       — Built on @alisababivip/react
│       │   ├── stores/           — Zustand state (signals, watchlist)
│       │   └── hooks/            — useSignalStream (WebSocket)
│       └── public/
│
├── 🔧 turbo.json
├── 📋 pnpm-workspace.yaml
└── 📝 CODEOWNERS                 → @nhamlapquoc @Dennis-nham
```

### Data flow diagram

```
  🌍 External Data Sources
  (News APIs · Social · SEC Filings · VN Stock Exchange)
            │
            ▼
  🌊 ingestion/          ← Apache Kafka (millions of events/sec)
     Topic: raw.documents
            │
            ▼
  🐇 orchestrator/       ← RabbitMQ routes tasks to workers
     ┌──────┴──────┐
     ▼             ▼
  🧠 nlp-         🔍 nlp-
     sentiment/      ner/
  (FinBERT+RAG)  (Entity extraction)
     └──────┬──────┘
            │  structured signals
            ▼
  🌐 api-gateway/        ← gRPC · REST · WebSocket
            │
            ▼
  📊 dashboard/          ← Real-time trading signals UI
  🔌 Enterprise clients  ← SDK: @nlp-labs-vietnam/nlp-sdk
```

---

## 🧠 `core-nlp` — Vietnamese NLP Research & Models

> Low-resource language research · Transformer fine-tuning · Data pipeline

```
📁 core-nlp/
│
├── 🤖 models/                    Fine-tuned model checkpoints
│   ├── vi-bert-base/             — Vietnamese BERT (PhoBERT-based)
│   ├── vi-finbert/               — Financial Vietnamese BERT
│   ├── vi-ner/                   — Vietnamese NER model
│   └── vi-sentiment/             — Vietnamese sentiment classifier
│
├── 🔩 pipeline/                  Data preprocessing & training pipeline
│   ├── src/tokenizer/            — Vietnamese word segmentation
│   ├── src/augmentation/         — Data augmentation strategies
│   ├── src/training/             — HuggingFace Trainer configs
│   └── src/evaluation/           — Benchmark scripts (VLUE, ViNLI)
│
├── 🔬 research/                  Jupyter notebooks & experiments
│   ├── notebooks/
│   │   ├── 01_eda_vietnamese_corpus.ipynb
│   │   ├── 02_finetune_phobert.ipynb
│   │   ├── 03_rag_solar_advisor.ipynb    ← 🌞 Solar PV RAG pipeline
│   │   └── 04_rl_signal_feedback.ipynb
│   └── papers/                   — Internal research notes
│
└── 📦 packages/
    └── 🔤 vi-tokenizer/          Vietnamese tokenizer (npm package)
        ├── src/                  — Syllable + word-level tokenization
        └── dist/                 — @nlp-labs-vietnam/vi-tokenizer
```

---

## 📦 `wikialisa-labs` — Satellite Packages & Open Standards

> Tree-sitter · Educational resources · Automation tooling

```
📁 wikialisa-labs/
│
├── 📦 packages/
│   ├── 🌳 tree-sitter-vi/        Vietnamese grammar for Tree-sitter
│   │   ├── grammar.js            — Tree-sitter grammar definition
│   │   ├── src/                  — C source (compiled parser)
│   │   └── bindings/             — Node.js + Python bindings
│   │
│   └── 📚 open-standards/        Educational resources & open standards
│       ├── curricula/            — NLP learning paths (EN/VI)
│       ├── datasets/             — Open Vietnamese NLP datasets
│       └── benchmarks/           — Standardized evaluation sets
│
└── 🔧 tools/
    └── ⚡ automation/            CI/CD helpers & dev tooling
        ├── scripts/              — Repo setup, release scripts
        └── generators/           — Code generators (component scaffolding)
```

---

## ⚙️ `.github` — Org-level Governance

```
📁 .github/
│
├── 📝 CODEOWNERS                 Auto-assign reviewers on PR
├── 👥 MAINTAINERS.md             Role assignments (EVerest-style)
├── 📋 FUNDING.yml                GitHub Sponsors configuration
│
├── 🗂 profile/
│   └── README.md                 Org public profile page
│
├── 🔄 workflows/
│   ├── ci.yml                    — Turborepo lint + test + build
│   ├── release.yml               — pnpm publish → npm registry
│   └── security.yml              — Dependabot + CodeQL scan
│
└── 📋 ISSUE_TEMPLATE/
    ├── bug_report.md
    ├── feature_request.md
    └── sponsorship_inquiry.md    ← Structured sponsor intake form
```

---

## 📐 Cross-repo Dependency Graph

```
                    ┌─────────────────────┐
                    │   @alisababivip/*    │
                    │  (ios-design-system) │
                    │  tokens · core ·     │
                    │  react · vue · blade │
                    └──────────┬──────────┘
                               │ UI components
               ┌───────────────┴───────────────┐
               ▼                               ▼
  ┌────────────────────┐           ┌────────────────────┐
  │ investment-platform│           │     core-nlp        │
  │   /apps/dashboard  │           │   /research/        │
  │  (uses @alisababivip│          │  (solar RAG 🌞)     │
  │   /react)          │           └──────────┬──────────┘
  └────────────────────┘                      │ models
               ▲                              ▼
               │              ┌────────────────────────┐
               │ signals API  │   investment-platform   │
               └──────────────│   /services/nlp-*/     │
                              │   (consumes vi-models)  │
                              └────────────────────────┘
                                         ▲
                              ┌──────────┴──────────┐
                              │   wikialisa-labs     │
                              │  tree-sitter-vi ·    │
                              │  open-standards      │
                              └─────────────────────┘
```

---

## 🛠 Tech Stack Summary

| Layer | Technology |
|---|---|
| **Monorepo** | Turborepo · pnpm workspaces |
| **Frontend** | React 18 · Vue 3 · Vite · VitePress |
| **Design system** | Apple HIG · CSS Custom Properties · Web Components |
| **Backend** | Node.js (Fastify) · gRPC · WebSocket |
| **Message streaming** | Apache Kafka |
| **Task orchestration** | RabbitMQ |
| **AI / NLP** | HuggingFace Transformers · PyTorch · FinBERT · PhoBERT |
| **RAG** | Milvus / Qdrant · LangChain |
| **Reinforcement Learning** | Custom RL feedback loop (signal → market outcome) |
| **Infrastructure** | Nginx · PM2 · Docker · GitHub Actions CI/CD |
| **Database** | PostgreSQL · Redis · Milvus (vector) |
| **Package registry** | npm (`@alisababivip/*`, `@nlp-labs-vietnam/*`) |

---

<div align="center">

**NLP Group** · Kiến tạo hệ sinh thái đổi mới bền vững  
Năng lượng xanh · Xe điện · Công nghệ · Trí tuệ nhân tạo · ESG

[@Dennis-nham](https://github.com/Dennis-nham) · [@thekingbao](https://github.com/thekingbao) · [@nhamlapquoc](https://github.com/nhamlapquoc) · [@wikialisalabs](https://github.com/wikialisalabs)

</div>
