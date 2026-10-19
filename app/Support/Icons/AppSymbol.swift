// =============================================================================
// AppSymbol.swift
// NLP Group — SF Symbols Type-Safe Enum
//
// AUTO-GENERATED — DO NOT EDIT MANUALLY
// Source: app/Support/Icons/NlpIcon.php
// Command: php artisan nlp:generate-swift
// Generated: 2026-10-13 12:41 | Tokens: 38
// Sync endpoint: GET /api/nlp-icons
// =============================================================================

import SwiftUI
import UIKit

// MARK: - Base Protocol

protocol AppSymbolProtocol: Hashable {
    var systemName: String { get }
    var semantic: String   { get }
    var tintColor: Color   { get }
    var renderingMode: SymbolRenderingMode { get }
}

extension AppSymbolProtocol {
    var image: Image { Image(systemName: systemName) }

    func uiImage(configuration: UIImage.Configuration? = nil) -> UIImage? {
        UIImage(systemName: systemName, withConfiguration: configuration)
    }

    var tintedImage: some View {
        image.symbolRenderingMode(renderingMode).foregroundStyle(tintColor)
    }
}

extension Image {
    init(symbol: any AppSymbolProtocol) { self.init(systemName: symbol.systemName) }
}

extension UIImageView {
    func setSymbol(_ symbol: any AppSymbolProtocol,
                   pointSize: CGFloat = 20,
                   weight: UIImage.SymbolWeight = .regular) {
        let cfg = UIImage.SymbolConfiguration(pointSize: pointSize, weight: weight)
        image     = symbol.uiImage(configuration: cfg)
        tintColor = UIColor(symbol.tintColor)
    }
}

// MARK: - Color Helper

extension Color {
    init(hex: String) {
        let hex = hex.trimmingCharacters(in: CharacterSet.alphanumerics.inverted)
        var int: UInt64 = 0
        Scanner(string: hex).scanHexInt64(&int)
        let r = Double((int >> 16) & 0xFF) / 255
        let g = Double((int >> 8)  & 0xFF) / 255
        let b = Double(int         & 0xFF) / 255
        self.init(.sRGB, red: r, green: g, blue: b, opacity: 1)
    }
}

// MARK: - AppSymbol

enum AppSymbol {

    // ── Energy ─────────────────────────────────────────────
    enum Energy: String, CaseIterable, AppSymbolProtocol {
        case solar = "sun.max.fill"
        case battery = "battery.100"
        case bolt = "bolt.fill"
        case tool = "wrench.fill"
        case tools = "wrench.and.screwdriver.fill"
        case chartDown = "chart.line.downtrend.xyaxis"

        var systemName: String { rawValue }

        var semantic: String {
            switch self {
            case .solar: return "solar"
            case .battery: return "battery"
            case .bolt: return "bolt"
            case .tool: return "tool"
            case .tools: return "tools"
            case .chartDown: return "chart-down"
            }
        }

        var tintColor: Color {
            switch self {
            case .solar: return Color(hex: "#d97706")
            case .battery: return Color(hex: "#1d4ed8")
            case .bolt: return Color(hex: "#0369a1")
            case .tool: return Color(hex: "#475569")
            case .tools: return Color(hex: "#475569")
            case .chartDown: return Color(hex: "#dc2626")
            }
        }

        var renderingMode: SymbolRenderingMode {
            switch self {
            case .solar: return .multicolor
            case .battery: return .hierarchical
            case .bolt: return .multicolor
            default: return .monochrome
            }
        }
    }

    // ── EVCharging ─────────────────────────────────────────────
    enum EVCharging: String, CaseIterable, AppSymbolProtocol {
        case plug = "powerplug.fill"
        case evCar = "bolt.car.fill"
        case screen = "desktopcomputer"
        case mobile = "iphone"
        case truck = "box.truck.fill"
        case partner = "person.2.fill"

        var systemName: String { rawValue }

        var semantic: String {
            switch self {
            case .plug: return "plug"
            case .evCar: return "ev-car"
            case .screen: return "screen"
            case .mobile: return "mobile"
            case .truck: return "truck"
            case .partner: return "partner"
            }
        }

        var tintColor: Color {
            switch self {
            case .plug: return Color(hex: "#1e40af")
            case .evCar: return Color(hex: "#0f4c75")
            case .screen: return Color(hex: "#334155")
            case .mobile: return Color(hex: "#0284c7")
            case .truck: return Color(hex: "#c2410c")
            case .partner: return Color(hex: "#15803d")
            }
        }

        var renderingMode: SymbolRenderingMode {
            switch self {
            case .plug: return .palette
            case .evCar: return .hierarchical
            case .partner: return .hierarchical
            default: return .monochrome
            }
        }
    }

    // ── Technology ─────────────────────────────────────────────
    enum Technology: String, CaseIterable, AppSymbolProtocol {
        case laptop = "laptopcomputer"
        case globe = "globe"
        case factory = "building.2.fill"
        case link = "link"
        case cloud = "cloud.fill"
        case package = "shippingbox.fill"
        case rocket = "paperplane.fill"

        var systemName: String { rawValue }

        var semantic: String {
            switch self {
            case .laptop: return "laptop"
            case .globe: return "globe"
            case .factory: return "factory"
            case .link: return "link"
            case .cloud: return "cloud"
            case .package: return "package"
            case .rocket: return "rocket"
            }
        }

        var tintColor: Color {
            switch self {
            case .laptop: return Color(hex: "#0e7490")
            case .globe: return Color(hex: "#1d4ed8")
            case .factory: return Color(hex: "#374151")
            case .link: return Color(hex: "#7c3aed")
            case .cloud: return Color(hex: "#0369a1")
            case .package: return Color(hex: "#64748b")
            case .rocket: return Color(hex: "#be123c")
            }
        }

        var renderingMode: SymbolRenderingMode {
            switch self {
            case .globe: return .multicolor
            case .cloud: return .multicolor
            case .rocket: return .palette
            default: return .monochrome
            }
        }
    }

    // ── AIDigital ─────────────────────────────────────────────
    enum AIDigital: String, CaseIterable, AppSymbolProtocol {
        case ai = "cpu.fill"
        case chat = "bubble.left.and.bubble.right.fill"
        case chart = "chart.bar.xaxis"
        case eye = "eye.fill"
        case chartUp = "chart.line.uptrend.xyaxis"
        case gear = "gearshape.fill"

        var systemName: String { rawValue }

        var semantic: String {
            switch self {
            case .ai: return "ai"
            case .chat: return "chat"
            case .chart: return "chart"
            case .eye: return "eye"
            case .chartUp: return "chart-up"
            case .gear: return "gear"
            }
        }

        var tintColor: Color {
            switch self {
            case .ai: return Color(hex: "#4338ca")
            case .chat: return Color(hex: "#1d4ed8")
            case .chart: return Color(hex: "#6d28d9")
            case .eye: return Color(hex: "#0891b2")
            case .chartUp: return Color(hex: "#15803d")
            case .gear: return Color(hex: "#475569")
            }
        }

        var renderingMode: SymbolRenderingMode {
            switch self {
            case .ai: return .hierarchical
            case .chat: return .palette
            case .chart: return .palette
            default: return .monochrome
            }
        }
    }

    // ── ESGCarbon ─────────────────────────────────────────────
    enum ESGCarbon: String, CaseIterable, AppSymbolProtocol {
        case tree = "tree.fill"
        case leaf = "leaf.fill"
        case wind = "wind"
        case building = "building.fill"
        case target = "scope"
        case check = "checkmark.seal.fill"

        var systemName: String { rawValue }

        var semantic: String {
            switch self {
            case .tree: return "tree"
            case .leaf: return "leaf"
            case .wind: return "wind"
            case .building: return "building"
            case .target: return "target"
            case .check: return "check"
            }
        }

        var tintColor: Color {
            switch self {
            case .tree: return Color(hex: "#16a34a")
            case .leaf: return Color(hex: "#15803d")
            case .wind: return Color(hex: "#0284c7")
            case .building: return Color(hex: "#334155")
            case .target: return Color(hex: "#dc2626")
            case .check: return Color(hex: "#16a34a")
            }
        }

        var renderingMode: SymbolRenderingMode {
            switch self {
            case .tree: return .multicolor
            case .leaf: return .multicolor
            case .check: return .multicolor
            default: return .monochrome
            }
        }
    }

    // ── Common ─────────────────────────────────────────────
    enum Common: String, CaseIterable, AppSymbolProtocol {
        case idea = "lightbulb.fill"
        case star = "star.fill"
        case list = "clipboard.fill"
        case pin = "mappin.and.ellipse"
        case work = "briefcase.fill"
        case money = "banknote.fill"
        case mail = "envelope.fill"

        var systemName: String { rawValue }

        var semantic: String {
            switch self {
            case .idea: return "idea"
            case .star: return "star"
            case .list: return "list"
            case .pin: return "pin"
            case .work: return "work"
            case .money: return "money"
            case .mail: return "mail"
            }
        }

        var tintColor: Color {
            switch self {
            case .idea: return Color(hex: "#ca8a04")
            case .star: return Color(hex: "#d97706")
            case .list: return Color(hex: "#1d4ed8")
            case .pin: return Color(hex: "#dc2626")
            case .work: return Color(hex: "#7c3aed")
            case .money: return Color(hex: "#15803d")
            case .mail: return Color(hex: "#1d4ed8")
            }
        }

        var renderingMode: SymbolRenderingMode {
            switch self {
            case .idea: return .multicolor
            case .star: return .multicolor
            case .pin: return .palette
            case .money: return .hierarchical
            case .mail: return .multicolor
            default: return .monochrome
            }
        }
    }

}

// MARK: - Animation Effects (↔ web .nlp-icon--{effect})

enum NLPIconEffect {
    @available(iOS 17, *) static let pulse  = PulseSymbolEffect.pulse
    @available(iOS 17, *) static let bounce = BounceSymbolEffect.bounce
    @available(iOS 17, *) static let spin   = RotateSymbolEffect.rotate
    @available(iOS 17, *) static let beat   = ScaleSymbolEffect.scale
    @available(iOS 17, *) static let shake  = WiggleSymbolEffect.wiggle
    @available(iOS 17, *) static let fade   = AppearSymbolEffect.appear
}
