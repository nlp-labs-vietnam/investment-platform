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

## 📈 `investment-platform` — NLP Group Investment Platform (Laravel + npm Monorepo)

> Laravel 11 · Sanctum · MySQL/AWS RDS · pnpm workspaces · Turborepo · npmjs.com

### Kiến trúc tổng quan — tách tầng 4 lớp

```
┌─────────────────────────────────────────────────────────────────────┐
│                     TẦNG npm CORE (publishable)                     │
│   @nlp-labs-vietnam/investment-types                                │
│   @nlp-labs-vietnam/investment-utils                                │
│   @nlp-labs-vietnam/investment-sdk                                  │
│   @nlp-labs-vietnam/investment-ui                                   │
└────────────────────────────┬────────────────────────────────────────┘
                             │ HTTP REST (Sanctum Bearer token)
┌────────────────────────────▼────────────────────────────────────────┐
│                  TẦNG BACKEND — services/api/                       │
│   Laravel 11 · 15 API Controllers · Sanctum Auth                   │
│   routes/api.php — 40+ endpoints                                    │
└────────────────────────────┬────────────────────────────────────────┘
                             │ Eloquent ORM
┌────────────────────────────▼────────────────────────────────────────┐
│                  TẦNG DATABASE                                      │
│   MySQL (local) · mysql_aws (AWS RDS)                               │
│   55 Models · 75+ migrations                                        │
└─────────────────────────────────────────────────────────────────────┘
```

### Cấu trúc thư mục monorepo

```
📁 investment-platform/              ← repo hiện tại (github.com/nlp-labs-vietnam/investment-platform)
│
├── 📋 pnpm-workspace.yaml           ← workspaces: packages/*, apps/*, services/*, tools/*
├── 🔧 turbo.json                    ← Turborepo pipeline: build → test → publish
│
├── 📦 packages/                     ← npm packages → publish lên npmjs.com
│   │
│   ├── 🗂 types/                    @nlp-labs-vietnam/investment-types
│   │   ├── src/
│   │   │   ├── property.d.ts        — Property, PropertySeo, Image, Address, Amenity
│   │   │   ├── user.d.ts            — User, UserKyc, UserSocial, Rank, Referral
│   │   │   ├── investment.d.ts      — Investment, PropertyShare, PropertyOffer
│   │   │   ├── finance.d.ts         — Deposit, Payout, Transaction, Fund, Gateway
│   │   │   ├── content.d.ts         — Page, Blog, BlogCategory, Content, ManageMenu
│   │   │   └── index.ts             — re-export tất cả types
│   │   ├── package.json
│   │   └── tsconfig.json
│   │
│   ├── 🛠 utils/                    @nlp-labs-vietnam/investment-utils
│   │   ├── src/
│   │   │   ├── currency.ts          — formatVND(), fractionNumber(), investmentRange()
│   │   │   │                          (chuyển đổi từ helpers.php: fractionNumber, getAmount)
│   │   │   ├── date.ts              — customDate(), customDateTime(), isExpired()
│   │   │   │                          (chuyển đổi từ helpers.php: customDate, dateTime)
│   │   │   ├── validators.ts        — validateInvestAmount(), validateKycField()
│   │   │   ├── string.ts            — stringToTitle(), getTitle(), hex2rgba()
│   │   │   │                          (chuyển đổi từ helpers.php: stringToTitle, hex2rgba)
│   │   │   └── index.ts
│   │   ├── package.json
│   │   └── vite.lib.config.ts
│   │
│   ├── 🌐 sdk/                      @nlp-labs-vietnam/investment-sdk
│   │   ├── src/
│   │   │   ├── client.ts            — NLPInvestmentClient (axios instance + Sanctum)
│   │   │   ├── auth.ts              — login(), register(), logout(), passwordReset()
│   │   │   │                          (từ routes/api.php: /register, /login, /logout)
│   │   │   ├── property.ts          — list(), detail(), invest(), investHistory()
│   │   │   │                          (từ routes/api.php: /property, /invest/property)
│   │   │   ├── deposit.ts           — addFund(), paymentRequest(), cardPayment()
│   │   │   │                          (từ routes/api.php: /add/fund, /payment/request)
│   │   │   ├── payout.ts            — payout(), checkAmount(), submit()
│   │   │   │                          (từ routes/api.php: /payout, /payout/submit/confirm)
│   │   │   ├── profile.ts           — getProfile(), updateProfile(), kycs()
│   │   │   │                          (từ routes/api.php: /user/profile, /kyc-submit)
│   │   │   ├── collection.ts        — wishlist(), addWishlist(), removeWishlist()
│   │   │   ├── support.ts           — ticketList(), ticketStore(), ticketReply()
│   │   │   └── index.ts             — export NLPInvestmentClient
│   │   ├── package.json
│   │   └── vite.lib.config.ts
│   │
│   └── 🎨 ui/                       @nlp-labs-vietnam/investment-ui  (đã đặt tên trong package.json gốc)
│       ├── src/
│       │   ├── tokens.scss          — NLP brand colors, typography, spacing
│       │   │                          (mở rộng từ resources/sass/_variables.scss)
│       │   ├── components.scss      — button, card, badge, property-card NLP custom
│       │   └── index.scss           — @forward tất cả
│       ├── package.json
│       └── vite.lib.config.ts
│
├── 🖥 apps/                         ← Consumer apps — KHÔNG publish lên npm
│   └── web/                         Frontend app (Vue/React — tương lai)
│       ├── src/
│       │   ├── pages/               — Dashboard, Properties, Portfolio, Profile
│       │   ├── components/          — Dùng @nlp/ui + @alisababivip/vue
│       │   └── composables/         — Dùng @nlp/sdk để gọi API
│       └── package.json
│
├── ⚙️  services/                    ← Backend services
│   └── api/                         ← Laravel app (toàn bộ code PHP hiện tại)
│       ├── app/
│       │   ├── Http/Controllers/Api/  — 15 API controllers (Auth, Property, Deposit...)
│       │   ├── Models/                — 55 Eloquent models
│       │   ├── Services/              — InvestmentService, BasicService, Gateway/*
│       │   └── Helpers/helpers.php    — fractionNumber(), basicControl(), footerData()
│       ├── routes/
│       │   ├── api.php              — 40+ REST endpoints (Sanctum protected)
│       │   ├── web.php              — Blade frontend routes
│       │   └── admin.php            — Admin panel routes
│       ├── database/
│       │   └── migrations/          — 75+ migrations từ 2014 → 2026
│       ├── resources/
│       │   ├── js/                  — app.js, bootstrap.js (axios + Bootstrap 5)
│       │   └── sass/                — app.scss, _variables.scss
│       └── composer.json
│
└── 🔧 tools/
    └── scripts/
        ├── gen-types.ts             — Tự động sinh TypeScript types từ PHP models
        └── check-api-sync.ts        — Kiểm tra endpoints trong sdk/ khớp routes/api.php
```

### Dependency graph giữa packages

```
@nlp-labs-vietnam/investment-types     (zero dependency — làm trước)
              │
     ┌────────┴────────┐
     ▼                 ▼
  utils/           sdk/               (peer dep: axios ^1.x)
     │                │
     └────────┬────────┘
              ▼
           ui/                        (SCSS — không phụ thuộc JS packages)
              │
              ▼
         apps/web                     (consumer — không publish)
```

### Data flow: Frontend → SDK → API → DB

```
  apps/web (Vue/React)
       │  import NLPInvestmentClient
       ▼
  @nlp-labs-vietnam/investment-sdk
       │  axios.post('/api/invest/property/42', payload)
       │  Header: Authorization: Bearer <sanctum_token>
       ▼
  services/api/  (Laravel 11)
       │  routes/api.php → PropertyController@investProperty
       │  InvestmentService@profitLossCalculate
       ▼
  Database (MySQL / AWS RDS)
       │  investments table, transactions table
       ▼
  JSON response → SDK → apps/web
```

### API endpoints đã có (routes/api.php)

| Nhóm | Endpoints | Controller |
|---|---|---|
| **Auth** | POST /register · /login · /logout · /password/reset | `AuthController` |
| **Property** | GET /property · /property-details/{id} · /investment/properties | `PropertyController` |
| **Investment** | POST /invest/property/{id} · GET /invest/history | `PropertyController` |
| **Deposit** | GET /add/fund · POST /payment/request · /card/payment | `DepositController` |
| **Payout** | GET /payout · POST /payout/submit/confirm | `PayoutController` |
| **Profile** | GET /user/profile · POST /update/user/profile · /kyc-submit | `ProfileController` |
| **Collection** | GET /wishlists · POST /wishlist/add · /wishlist/remove | `CollectionController` |
| **Market** | GET /share/market · POST /direct/buy/share/{id} | `PropertyController` |
| **Offer** | POST /property/make/offer · /offer/accept · /offer/reject | `PropertyController` |
| **Support** | GET /support/ticket/list · POST /support/ticket/store | `SupportTicketController` |
| **Transfer** | GET /money/transfer · POST /money/transfer/store | `MoneyTransferController` |
| **Ranking** | GET /rankings · /referral · /referral/bonus | `RankingController` |

### Lộ trình thực thi (thứ tự phụ thuộc)

```
Bước 1  git mv → đưa Laravel vào services/api/
        (giữ nguyên 100% code PHP, chỉ thay vị trí trong repo)

Bước 2  tạo packages/types/
        Viết interface TypeScript từ 55 Eloquent models
        Không có dependency → build độc lập hoàn toàn

Bước 3  tạo packages/utils/
        Chuyển đổi helpers.php sang TypeScript
        fractionNumber() → currency.ts
        customDate()     → date.ts
        stringToTitle()  → string.ts

Bước 4  tạo packages/sdk/
        Ánh xạ 40+ endpoints từ routes/api.php
        Vite lib mode → xuất ESM + CJS + .d.ts

Bước 5  nâng cấp packages/ui/
        Mở rộng _variables.scss → design tokens đầy đủ
        Tách Blade components → SCSS độc lập

Bước 6  CI/CD: pnpm changeset → npm publish
        GitHub Actions: release.yml
        turbo run build → turbo run publish
```

### Turbo pipeline (cập nhật)

```jsonc
// turbo.json
{
  "pipeline": {
    "build":   { "dependsOn": ["^build"], "outputs": ["dist/**"] },
    "test":    { "dependsOn": ["build"] },
    "typecheck": { "dependsOn": ["^build"] },
    "publish": { "dependsOn": ["build", "test"], "cache": false },
    "clean":   { "cache": false }
  }
}
// Thứ tự Turbo tự tính: types → utils → sdk → ui → apps/web
```

### Tech stack — investment-platform

| Tầng | Công nghệ |
|---|---|
| **Backend** | Laravel 11 · PHP 8.1 · Sanctum · Eloquent ORM |
| **Database** | MySQL (local) · AWS RDS · Redis (cache) |
| **Payment gateways** | Stripe · Razorpay · Flutterwave · VietQR · 10+ gateways |
| **Frontend (hiện tại)** | Blade templates · Bootstrap 5 · Vite · SCSS |
| **Frontend (tương lai)** | Vue 3 / React 18 · `apps/web/` |
| **npm packages** | `@nlp-labs-vietnam/investment-types/utils/sdk/ui` |
| **Monorepo** | pnpm workspaces · Turborepo |
| **CI/CD** | GitHub Actions · pnpm changeset · npm publish |
| **Auth** | Laravel Sanctum (Bearer token) · Socialite (OAuth) · 2FA |

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
