<?php

namespace App\Console\Commands;

use App\Support\Icons\NlpIcon;
use Illuminate\Console\Command;

/**
 * php artisan nlp:generate-swift
 *
 * Đọc NlpIcon.php (SSOT) → xuất AppSymbol.swift đồng bộ hoàn toàn.
 * Chạy sau mỗi lần thêm/sửa token trong NlpIcon.php.
 */
class GenerateAppSymbolSwift extends Command
{
    protected $signature   = 'nlp:generate-swift
                                {--out= : Đường dẫn file output (mặc định: app/Support/Icons/AppSymbol.swift)}
                                {--dry-run : In ra stdout, không ghi file}';

    protected $description = 'Generate AppSymbol.swift từ NlpIcon.php (Single Source of Truth)';

    // Nhóm token theo domain để tạo nested enum
    private array $domainEnumMap = [
        'energy'     => 'Energy',
        'ev'         => 'EVCharging',
        'technology' => 'Technology',
        'ai'         => 'AIDigital',
        'esg'        => 'ESGCarbon',
        'common'     => 'Common',
    ];

    // Mapping: SF Symbol rendering mode gợi ý theo từng token
    private array $renderingModeMap = [
        // multicolor
        'solar'    => 'multicolor', 'globe'   => 'multicolor',
        'cloud'    => 'multicolor', 'tree'    => 'multicolor',
        'leaf'     => 'multicolor', 'check'   => 'multicolor',
        'idea'     => 'multicolor', 'star'    => 'multicolor',
        'mail'     => 'multicolor', 'bolt'    => 'multicolor',
        // palette
        'plug'     => 'palette',   'chat'    => 'palette',
        'chart'    => 'palette',   'rocket'  => 'palette',
        'pin'      => 'palette',
        // hierarchical
        'battery'  => 'hierarchical', 'ai'     => 'hierarchical',
        'partner'  => 'hierarchical', 'ev-car' => 'hierarchical',
        'money'    => 'hierarchical',
    ];

    public function handle(): int
    {
        $tokens  = NlpIcon::all();
        $outPath = $this->option('out')
            ?? base_path('app/Support/Icons/AppSymbol.swift');
        $dryRun  = $this->option('dry-run');

        // Nhóm theo domain
        $grouped = [];
        foreach ($tokens as $semantic => $token) {
            $grouped[$token['domain']][$semantic] = $token;
        }

        $lines = [];

        // ── File header ──────────────────────────────────────────────
        $date  = now()->format('Y-m-d H:i');
        $count = count($tokens);
        $lines[] = <<<SWIFT
// =============================================================================
// AppSymbol.swift
// NLP Group — SF Symbols Type-Safe Enum
//
// AUTO-GENERATED — DO NOT EDIT MANUALLY
// Source: app/Support/Icons/NlpIcon.php
// Command: php artisan nlp:generate-swift
// Generated: {$date} | Tokens: {$count}
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

SWIFT;

        // ── Nested enum cho từng domain ──────────────────────────────
        foreach ($this->domainEnumMap as $domain => $enumName) {
            if (empty($grouped[$domain])) {
                continue;
            }

            $domainTokens = $grouped[$domain];
            $lines[]      = "    // ── {$enumName} ─────────────────────────────────────────────";
            $lines[]      = "    enum {$enumName}: String, CaseIterable, AppSymbolProtocol {";

            // cases
            foreach ($domainTokens as $semantic => $token) {
                $caseName = $this->toCamelCase($semantic);
                $sf       = addslashes($token['sf']);
                $lines[]  = "        case {$caseName} = \"{$sf}\"";
            }

            // systemName
            $lines[] = '';
            $lines[] = '        var systemName: String { rawValue }';

            // semantic
            $lines[] = '';
            $lines[] = '        var semantic: String {';
            $lines[] = '            switch self {';
            foreach ($domainTokens as $semantic => $token) {
                $caseName = $this->toCamelCase($semantic);
                $lines[]  = "            case .{$caseName}: return \"{$semantic}\"";
            }
            $lines[] = '            }';
            $lines[] = '        }';

            // tintColor
            $lines[] = '';
            $lines[] = '        var tintColor: Color {';
            $lines[] = '            switch self {';
            foreach ($domainTokens as $semantic => $token) {
                $caseName = $this->toCamelCase($semantic);
                $color    = $token['color'];
                $lines[]  = "            case .{$caseName}: return Color(hex: \"{$color}\")";
            }
            $lines[] = '            }';
            $lines[] = '        }';

            // renderingMode
            $lines[] = '';
            $lines[] = '        var renderingMode: SymbolRenderingMode {';
            $lines[] = '            switch self {';
            $hasExplicit = false;
            foreach ($domainTokens as $semantic => $token) {
                if (isset($this->renderingModeMap[$semantic])) {
                    $caseName = $this->toCamelCase($semantic);
                    $mode     = $this->renderingModeMap[$semantic];
                    $lines[]  = "            case .{$caseName}: return .{$mode}";
                    $hasExplicit = true;
                }
            }
            $lines[] = ($hasExplicit ? '            default:' : '            _:') . ' return .monochrome';
            $lines[] = '            }';
            $lines[] = '        }';
            $lines[] = '    }';
            $lines[] = '';
        }

        // ── Close AppSymbol enum ─────────────────────────────────────
        $lines[] = '}';
        $lines[] = '';

        // ── Animation Effects ────────────────────────────────────────
        $lines[] = <<<'SWIFT'
// MARK: - Animation Effects (↔ web .nlp-icon--{effect})

enum NLPIconEffect {
    @available(iOS 17, *) static let pulse  = PulseSymbolEffect.pulse
    @available(iOS 17, *) static let bounce = BounceSymbolEffect.bounce
    @available(iOS 17, *) static let spin   = RotateSymbolEffect.rotate
    @available(iOS 17, *) static let beat   = ScaleSymbolEffect.scale
    @available(iOS 17, *) static let shake  = WiggleSymbolEffect.wiggle
    @available(iOS 17, *) static let fade   = AppearSymbolEffect.appear
}
SWIFT;

        $output = implode("\n", $lines) . "\n";

        if ($dryRun) {
            $this->line($output);
            $this->newLine();
            $this->info("Dry-run: {$count} tokens → would write to {$outPath}");
            return self::SUCCESS;
        }

        file_put_contents($outPath, $output);
        $this->info("✅ Generated {$count} tokens → {$outPath}");
        $this->table(
            ['Domain', 'Enum', 'Tokens'],
            collect($this->domainEnumMap)->map(function ($enumName, $domain) use ($grouped) {
                return [$domain, $enumName, count($grouped[$domain] ?? [])];
            })->values()->toArray()
        );

        return self::SUCCESS;
    }

    // ── Helpers ──────────────────────────────────────────────────────

    /** "ev-car" → "evCar", "chart-down" → "chartDown" */
    private function toCamelCase(string $semantic): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $semantic))));
    }
}
