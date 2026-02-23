import { useRef, useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { createPortal } from "react-dom";

import {
  Mountain,
  Gem,
  Truck,
  Clock,
  DollarSign,
  BarChart3,
  Activity,
  Fuel,
  Layers,
  Wallet,
  ArrowUpRight,
  CalendarDays,
  MapPin,
  Hammer,
  Zap,
  Route,
  CloudRain,
  Droplets,
  ClipboardCheck,
  ChevronDown,
  Search,
  Check,
  X,
} from "lucide-react";

import { fetchDashboardLocal } from "../../services/dashboardLoader";

import { fmt } from "../../utils/format";
import { cTxt } from "../../utils/colors";

import Pill from "../../components/ui/Pill";
import Section from "../../components/ui/Section";
import IconBox from "../../components/ui/IconBox";
import Bar from "../../components/ui/Bar";

import HeroMetric from "../../components/cards/HeroMetric";
import KpiTile from "../../components/cards/KpiTile";
import PeriodRow from "../../components/cards/PeriodRow";
import FleetTile from "../../components/cards/FleetTile";
import RankTable from "../../components/cards/RankTable";
import FinanceHero from "../../components/cards/FinanceHero";
import WorkingHoursHero from "../../components/cards/WorkingHoursHero";

// ==================== SitePicker ====================
function SitePicker({ site, setSite, sites }) {
  const [open, setOpen] = useState(false);
  const [q, setQ] = useState("");

  useEffect(() => {
    if (!open) return;

    const prev = document.body.style.overflow;
    document.body.style.overflow = "hidden";

    return () => {
      document.body.style.overflow = prev;
    };
  }, [open]);

  const filtered = sites.filter((s) =>
    s.toLowerCase().includes(q.trim().toLowerCase()),
  );

  return (
    <div style={{ position: "relative" }}>
      {/* ===== Trigger ===== */}
      <button
        onClick={() => setOpen(true)}
        style={{
          width: "100%",
          border: "none",
          cursor: "pointer",
          background: "#f1f5f9",
          borderRadius: 14,
          padding: "10px 12px",
          display: "flex",
          alignItems: "center",
          justifyContent: "space-between",
          gap: 12,
        }}
      >
        <div style={{ display: "flex", alignItems: "center", gap: 10 }}>
          <div
            style={{
              width: 34,
              height: 34,
              borderRadius: 12,
              background: "linear-gradient(135deg, #f59e0b, #f97316)",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              boxShadow: "0 2px 10px rgba(249,115,22,.25)",
            }}
          >
            <MapPin size={16} color="#fff" />
          </div>

          <div style={{ textAlign: "left" }}>
            <div style={{ fontSize: 10, fontWeight: 800, color: "#64748b" }}>
              Selected Site
            </div>
            <div style={{ fontSize: 13, fontWeight: 900, color: "#0f172a" }}>
              {site}
            </div>
          </div>
        </div>

        <ChevronDown size={18} color="#64748b" />
      </button>

      {/* ===== Bottom Sheet ===== */}
      {open &&
        createPortal(
          <div
            onClick={() => setOpen(false)}
            style={{
              position: "fixed",
              inset: 0,
              zIndex: 9999,
              background: "rgba(15,23,42,.45)",
              backdropFilter: "blur(8px)",
              WebkitBackdropFilter: "blur(8px)",

              display: "flex",
              justifyContent: "center",
              alignItems: "flex-start",

              paddingTop: 18,
              paddingLeft: 12,
              paddingRight: 12,
              paddingBottom: 12,
            }}
          >
            <div
              onClick={(e) => e.stopPropagation()}
              style={{
                width: "min(520px, 100%)",
                background: "#fff",
                borderRadius: 22,
                boxShadow: "0 18px 60px rgba(0,0,0,.25)",
                overflow: "hidden",

                maxHeight: "70vh",
                display: "flex",
                flexDirection: "column",
              }}
            >
              {/* Header */}
              <div
                style={{
                  padding: 14,
                  borderBottom: "1px solid #f1f5f9",
                  display: "flex",
                  alignItems: "center",
                  justifyContent: "space-between",
                }}
              >
                <div>
                  <div style={{ fontSize: 14, fontWeight: 900 }}>
                    Choose Site
                  </div>
                  <div style={{ fontSize: 11, color: "#94a3b8", marginTop: 2 }}>
                    Search & select site
                  </div>
                </div>

                <button
                  onClick={() => setOpen(false)}
                  style={{
                    border: "none",
                    background: "#f1f5f9",
                    borderRadius: 12,
                    padding: 8,
                    cursor: "pointer",
                  }}
                  aria-label="Close"
                >
                  <X size={16} />
                </button>
              </div>

              {/* Search */}
              <div style={{ padding: 14 }}>
                <div
                  style={{
                    display: "flex",
                    alignItems: "center",
                    gap: 10,
                    padding: "10px 12px",
                    borderRadius: 14,
                    border: "1px solid #e2e8f0",
                  }}
                >
                  <Search size={14} color="#94a3b8" />
                  <input
                    value={q}
                    onChange={(e) => setQ(e.target.value)}
                    placeholder="Search site (KCP, BCP, ACP...)"
                    style={{
                      border: "none",
                      outline: "none",
                      width: "100%",
                      fontSize: 12,
                      fontWeight: 700,
                      fontFamily: "inherit",
                    }}
                  />
                </div>
              </div>

              {/* List */}
              <div
                style={{
                  flex: 1,
                  overflowY: "auto",
                  padding: "0 14px 14px",
                }}
              >
                {filtered.map((s) => {
                  const active = s === site;

                  return (
                    <button
                      key={s}
                      onClick={() => {
                        setSite(s);
                        setOpen(false);
                      }}
                      style={{
                        width: "100%",
                        border: "none",
                        cursor: "pointer",
                        padding: 12,
                        borderRadius: 16,
                        marginBottom: 8,
                        background: active ? "#fff7ed" : "#f8fafc",
                        border: active
                          ? "1px solid #fdba74"
                          : "1px solid #f1f5f9",
                        display: "flex",
                        alignItems: "center",
                        justifyContent: "space-between",
                        gap: 12,
                      }}
                    >
                      <div
                        style={{
                          display: "flex",
                          alignItems: "center",
                          gap: 10,
                        }}
                      >
                        <div
                          style={{
                            width: 34,
                            height: 34,
                            borderRadius: 12,
                            background: active
                              ? "linear-gradient(135deg, #f59e0b, #f97316)"
                              : "#e2e8f0",
                            display: "flex",
                            alignItems: "center",
                            justifyContent: "center",
                          }}
                        >
                          <MapPin
                            size={16}
                            color={active ? "#fff" : "#64748b"}
                          />
                        </div>
                        <div>
                          <div style={{ fontSize: 13, fontWeight: 900 }}>
                            {s}
                          </div>
                          <div style={{ fontSize: 10, color: "#94a3b8" }}>
                            Tap to apply
                          </div>
                        </div>
                      </div>

                      {active && <Check size={16} color="#f97316" />}
                    </button>
                  );
                })}
              </div>
            </div>
          </div>,
          document.body,
        )}
    </div>
  );
}

// ==================== Dashboard ====================
export default function Dashboard() {
  const [site, setSite] = useState("ALL");
  const [period, setPeriod] = useState("daily");
  const [tab, setTab] = useState("production");

  const fleetRef = useRef(null);
  const [fleetActive, setFleetActive] = useState(0);
  const nav = useNavigate();

  const [SITES, setSITES] = useState({});
  const [FLEET, setFLEET] = useState({});
  const [RANKING, setRANKING] = useState([]);
  const [FIN, setFIN] = useState({});
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let alive = true;

    (async () => {
      try {
        setLoading(true);
        const data = await fetchDashboardLocal();
        if (!alive) return;

        setSITES(data.SITES || {});
        setFLEET(data.FLEET || {});
        setRANKING(data.RANKING || []);
        setFIN(data.FIN || {});

        const keys = Object.keys(data.SITES || {});
        if (keys.length && !(data.SITES || {})[site]) {
          setSite(keys[0]);
        }
      } finally {
        if (!alive) return;
        setLoading(false);
      }
    })();

    return () => {
      alive = false;
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const fleetKeys = Object.keys(FLEET || {});
  const d =
    (SITES && SITES[site]) ||
    (SITES && SITES.ALL) ||
    (SITES && SITES[Object.keys(SITES || {})[0]]);

  // render loading sederhana
  if (loading || !d) {
    return (
      <div className="page">
        <div className="shell" style={{ padding: 18 }}>
          Loading dashboard data...
        </div>
      </div>
    );
  }

  const fmtRp = (n) => {
    const v = Number(n ?? 0);
    return "Rp " + v.toLocaleString("id-ID");
  };

  const pct = (a, p) => {
    const A = Number(a ?? 0);
    const P = Number(p ?? 0);
    if (!P) return 0;
    return Math.round((A / P) * 100);
  };

  const pMap = {
    daily: {
      ob: d.daily?.ob ?? 0,
      coal: d.daily?.coal ?? 0,
      obPct: d.dailyPct?.ob ?? 0,
      coalPct: d.dailyPct?.coal ?? 0,
    },
    wtd: {
      ob: d.wtd?.ob ?? 0,
      coal: d.wtd?.coal ?? 0,
      obPct: d.wtdPct?.ob ?? 0,
      coalPct: d.wtdPct?.coal ?? 0,
    },
    mtd: {
      ob: d.mtd?.ob ?? 0,
      coal: d.mtd?.coal ?? 0,
      obPct: d.mtdPct?.ob ?? 0,
      coalPct: d.mtdPct?.coal ?? 0,
    },
    ytd: {
      ob: d.ytd?.ob ?? 0,
      coal: d.ytd?.coal ?? 0,
      obPct: d.ytdPct?.ob ?? 0,
      coalPct: d.ytdPct?.coal ?? 0,
    },
  };
  const p = pMap[period];

  // Production detail helpers
  const pd = d.productionDetails;
  const pdCoalBcm = pd?.coalBcm?.[period] ?? { actual: 0, plan: 0, pct: 0 };
  const pdCoalHaul = pd?.coalHauling?.[period] ?? {
    actual: 0,
    plan: 0,
    pct: 0,
  };
  const pdWaste = pd?.wasteRemoval?.[period] ?? { actual: 0, plan: 0, pct: 0 };
  const pdMat = pd?.materialRemoved?.[period] ?? {
    actual: 0,
    plan: 0,
    pct: 0,
  };

  const onFleetScroll = () => {
    const el = fleetRef.current;
    if (!el) return;

    // Tablet/Desktop: fleet jadi grid, jadi skip hitung active slide
    if (typeof window !== "undefined" && window.innerWidth >= 768) return;

    const w = el.clientWidth; // 1 slide = 1 viewport width
    const idx = Math.round(el.scrollLeft / w);
    setFleetActive(Math.max(0, Math.min(idx, fleetKeys.length - 1)));
  };

  const goToFleet = (idx) => {
    const el = fleetRef.current;
    if (!el) return;
    el.scrollTo({
      left: idx * el.clientWidth,
      behavior: "smooth",
    });
  };

  return (
    <div className="page">
      <div className="shell">
        <link
          href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800&display=swap"
          rel="stylesheet"
        />

        {/* ── HEADER ── */}
        <div
          style={{
            position: "sticky",
            top: 0,
            zIndex: 50,
            background: "white",
            backdropFilter: "blur(20px) saturate(180%)",
            WebkitBackdropFilter: "blur(20px) saturate(180%)",
            borderBottom: "1px solid #f1f5f9",
            padding: "14px 18px 12px",
          }}
        >
          <div
            style={{
              display: "flex",
              alignItems: "center",
              justifyContent: "space-between",
              marginBottom: 12,
              gap: 12,
            }}
          >
            <div style={{ minWidth: 0 }}>
              <div
                style={{
                  fontSize: 18,
                  fontWeight: 800,
                  color: "#1e293b",
                  letterSpacing: -0.3,
                  whiteSpace: "nowrap",
                  overflow: "hidden",
                  textOverflow: "ellipsis",
                }}
              >
                Production Dashboard
              </div>
              <div
                style={{
                  fontSize: 11,
                  color: "#94a3b8",
                  marginTop: 2,
                  display: "flex",
                  alignItems: "center",
                  gap: 4,
                  flexWrap: "wrap",
                }}
              >
                <CalendarDays size={11} />
                {new Date().toLocaleDateString("en-US", {
                  weekday: "long",
                  day: "numeric",
                  month: "short",
                  year: "numeric",
                })}
              </div>
            </div>

            <div
              style={{
                width: 38,
                height: 38,
                borderRadius: 13,
                background: "linear-gradient(135deg, #f59e0b, #f97316)",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                boxShadow: "0 2px 10px rgba(249,115,22,.3)",
                overflow: "hidden",
                flex: "0 0 auto",
              }}
            >
              <img
                src="/Logo_Utama.svg"
                alt="Logo Utama"
                style={{
                  width: 22,
                  height: 22,
                  objectFit: "contain",
                  filter: "brightness(0) invert(1)",
                }}
              />
            </div>
          </div>

          {/* Site pills */}
          <SitePicker
            site={site}
            setSite={setSite}
            sites={Object.keys(SITES)}
          />
        </div>

        {/* ── NAV TABS ── */}
        <div
          style={{
            display: "flex",
            gap: 6,
            padding: "10px 18px",
            background: "rgba(255,255,255,.6)",
            borderBottom: "1px solid #f1f5f9",
            flexWrap: "nowrap",
          }}
        >
          {[
            { key: "production", label: "Production", Ico: BarChart3 },
            { key: "performance", label: "Performance", Ico: Zap },
            { key: "financial", label: "Financial", Ico: Wallet },
          ].map((t) => (
            <button
              key={t.key}
              onClick={() => setTab(t.key)}
              style={{
                flex: 1,
                padding: "9px 0",
                borderRadius: 11,
                border: "none",
                cursor: "pointer",
                fontSize: 11,
                fontWeight: 700,
                fontFamily: "inherit",
                background: tab === t.key ? "#fff" : "transparent",
                color: tab === t.key ? "#f97316" : "#94a3b8",
                boxShadow: tab === t.key ? "0 1px 8px rgba(0,0,0,.06)" : "none",
                transition: "all .25s",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                gap: 5,
                whiteSpace: "nowrap",
              }}
            >
              <t.Ico size={13} /> {t.label}
            </button>
          ))}
        </div>

        {/* ── CONTENT ── */}
        <div className="content">
          {/* ═══ PRODUCTION ═══ */}
          {tab === "production" && (
            <>
              <div
                style={{
                  display: "flex",
                  gap: 0,
                  background: "#f1f5f9",
                  borderRadius: 14,
                  padding: 3,
                  flexWrap: "wrap",
                }}
              >
                {["daily", "wtd", "mtd", "ytd"].map((pr) => (
                  <Pill
                    key={pr}
                    active={period === pr}
                    onClick={() => setPeriod(pr)}
                  >
                    {pr}
                  </Pill>
                ))}
              </div>

              <div className="grid-hero">
                <HeroMetric
                  label="Overburden"
                  Icon={Mountain}
                  value={p.ob}
                  unit="KBcm"
                  pct={p.obPct}
                  delay={100}
                />
                <HeroMetric
                  label="Coal"
                  Icon={Gem}
                  value={fmt(p.coal)}
                  unit="Metric Ton"
                  pct={p.coalPct}
                  delay={200}
                />
              </div>

              <Section>Production Details</Section>
              <div className="grid-kpi">
                <KpiTile
                  Icon={Layers}
                  label="Coal Tonase"
                  actual={fmt(pdCoalBcm.actual)}
                  plan={fmt(pdCoalBcm.plan)}
                  unit="BCM"
                  pct={pdCoalBcm.pct}
                  delay={140}
                />
                <KpiTile
                  Icon={Truck}
                  label="Coal Hauling"
                  actual={fmt(pdCoalHaul.actual)}
                  plan={fmt(pdCoalHaul.plan)}
                  unit="t"
                  pct={pdCoalHaul.pct}
                  delay={170}
                />
                <KpiTile
                  Icon={Droplets}
                  label="Waste Removal"
                  actual={fmt(pdWaste.actual)}
                  plan={fmt(pdWaste.plan)}
                  unit="t"
                  pct={pdWaste.pct}
                  delay={200}
                />
                <KpiTile
                  Icon={Activity}
                  label="Material Removed"
                  actual={fmt(pdMat.actual)}
                  plan={fmt(pdMat.plan)}
                  unit="t"
                  pct={pdMat.pct}
                  delay={230}
                />
              </div>

              <Section>Distance (Km)</Section>
              <div className="grid-kpi">
                <KpiTile
                  Icon={Route}
                  label="Dist. OB"
                  actual={d.distance?.ob?.actual ?? 0}
                  plan={d.distance?.ob?.plan ?? 0}
                  unit="km"
                  pct={d.distance?.ob?.pct ?? 0}
                  delay={160}
                />
                <KpiTile
                  Icon={Route}
                  label="Dist. CO"
                  actual={d.distance?.coal?.actual ?? 0}
                  plan={d.distance?.coal?.plan ?? 0}
                  unit="km"
                  pct={d.distance?.coal?.pct ?? 0}
                  delay={190}
                />
              </div>

              <Section>Daily Monitoring</Section>
              <div className="grid-kpi">
                <KpiTile
                  Icon={Truck}
                  label="N.Fleet"
                  actual={d.nfleet?.actual ?? 0}
                  plan={d.nfleet?.plan ?? 0}
                  unit=""
                  pct={d.nfleet?.pct ?? 0}
                  delay={150}
                />
                <KpiTile
                  Icon={Clock}
                  label="EWH"
                  actual={d.ewh?.actual ?? 0}
                  plan={d.ewh?.plan ?? 0}
                  unit="hrs"
                  pct={d.ewh?.pct ?? 0}
                  delay={200}
                />
                <KpiTile
                  Icon={Activity}
                  label="PDTY"
                  actual={Number(d.pdty?.actual ?? 0).toFixed(0)}
                  plan={Number(d.pdty?.plan ?? 0).toFixed(0)}
                  unit=""
                  pct={d.pdty?.pct ?? 0}
                  delay={250}
                />
                <KpiTile
                  Icon={DollarSign}
                  label="EBITDA"
                  actual="Rp 0"
                  plan="Rp 0"
                  unit=""
                  pct={d.ebitda?.pct ?? 0}
                  delay={300}
                />
              </div>

              <Section>Period Breakdown</Section>
              {["daily", "wtd", "mtd", "ytd"].map((pr, i) => {
                const pm = pMap[pr];
                const dd =
                  pr === "daily"
                    ? d.daily
                    : pr === "wtd"
                      ? d.wtd
                      : pr === "mtd"
                        ? d.mtd
                        : d.ytd;

                return (
                  <PeriodRow
                    key={pr}
                    period={pr.toUpperCase()}
                    ob={dd?.ob ?? 0}
                    coal={dd?.coal ?? 0}
                    obPct={pm.obPct}
                    coalPct={pm.coalPct}
                    delay={100 + i * 80}
                  />
                );
              })}
            </>
          )}

          {/* ═══ PERFORMANCE ═══ */}
          {tab === "performance" && (
            <>
              <WorkingHoursHero delay={100} />

              <Section>PA Summary</Section>
              <div className="grid-kpi">
                <KpiTile
                  Icon={Hammer}
                  label="PA Digger"
                  actual={d.paSummary?.digger?.actual ?? 0}
                  plan={d.paSummary?.digger?.plan ?? 0}
                  unit="%"
                  pct={d.paSummary?.digger?.pct ?? 0}
                  delay={120}
                />
                <KpiTile
                  Icon={Truck}
                  label="PA Hauler"
                  actual={d.paSummary?.hauler?.actual ?? 0}
                  plan={d.paSummary?.hauler?.plan ?? 0}
                  unit="%"
                  pct={d.paSummary?.hauler?.pct ?? 0}
                  delay={150}
                />
                <KpiTile
                  Icon={Mountain}
                  label="PA Dozer"
                  actual={d.paSummary?.dozer?.actual ?? 0}
                  plan={d.paSummary?.dozer?.plan ?? 0}
                  unit="%"
                  pct={d.paSummary?.dozer?.pct ?? 0}
                  delay={180}
                />
                <KpiTile
                  Icon={Route}
                  label="PA Grader"
                  actual={d.paSummary?.grader?.actual ?? 0}
                  plan={d.paSummary?.grader?.plan ?? 0}
                  unit="%"
                  pct={d.paSummary?.grader?.pct ?? 0}
                  delay={210}
                />
              </div>

              <Section>Fleet Performance</Section>

              <div
                style={{
                  display: "flex",
                  alignItems: "center",
                  justifyContent: "space-between",
                  marginTop: -6,
                  marginBottom: 6,
                }}
              >
                <div
                  style={{ fontSize: 12, fontWeight: 800, color: "#0f172a" }}
                >
                  {/* empty left */}
                </div>
                <button
                  onClick={() => nav("/fleet")}
                  style={{
                    border: "none",
                    background: "transparent",
                    color: "#f97316",
                    fontWeight: 800,
                    fontSize: 12,
                    cursor: "pointer",
                    padding: "6px 6px",
                  }}
                >
                  View All
                </button>
              </div>

              <div
                ref={fleetRef}
                onScroll={onFleetScroll}
                className="fleet-wrap hide-scrollbar"
              >
                {fleetKeys.map((name) => (
                  <div key={name} className="fleet-slide">
                    <FleetTile name={name} data={FLEET?.[name]} delay={0} />
                  </div>
                ))}
              </div>

              {/* dots */}
              <div>
                {(() => {
                  const total = fleetKeys.length;
                  const maxDots = Math.min(5, total);

                  let start = 0;
                  if (total > maxDots) {
                    if (fleetActive <= 2) start = 0;
                    else if (fleetActive >= total - 3) start = total - maxDots;
                    else start = fleetActive - 2;
                  }
                  const dots = Array.from(
                    { length: maxDots },
                    (_, i) => start + i,
                  );

                  return (
                    <div
                      className="fleet-dots"
                      style={{
                        display: "flex",
                        justifyContent: "center",
                        gap: 8,
                        marginTop: 6,
                        alignItems: "center",
                      }}
                    >
                      {start > 0 && (
                        <span
                          style={{
                            fontSize: 14,
                            color: "#cbd5e1",
                            lineHeight: 1,
                          }}
                        >
                          …
                        </span>
                      )}

                      {dots.map((idx) => (
                        <button
                          key={idx}
                          onClick={() => goToFleet(idx)}
                          style={{
                            width: idx === fleetActive ? 10 : 8,
                            height: idx === fleetActive ? 10 : 8,
                            borderRadius: 999,
                            border: "none",
                            cursor: "pointer",
                            background:
                              idx === fleetActive ? "#0f172a" : "#cbd5e1",
                            transition: "all .2s ease",
                          }}
                          aria-label={`Fleet slide ${idx + 1}`}
                        />
                      ))}

                      {start + maxDots < total && (
                        <span
                          style={{
                            fontSize: 14,
                            color: "#cbd5e1",
                            lineHeight: 1,
                          }}
                        >
                          …
                        </span>
                      )}
                    </div>
                  );
                })()}
              </div>

              <Section>Weather & Operational Loss</Section>
              <div className="grid-kpi">
                <KpiTile
                  Icon={CloudRain}
                  label="Rain Delay"
                  actual={d.weather?.rain?.actual ?? 0}
                  plan={d.weather?.rain?.plan ?? 0}
                  unit="hrs"
                  pct={d.weather?.rain?.pct ?? 0}
                  delay={120}
                />
                <KpiTile
                  Icon={CloudRain}
                  label="Slippery"
                  actual={d.weather?.slippery?.actual ?? 0}
                  plan={d.weather?.slippery?.plan ?? 0}
                  unit="hrs"
                  pct={d.weather?.slippery?.pct ?? 0}
                  delay={140}
                />
                <KpiTile
                  Icon={Droplets}
                  label="Rainfall"
                  actual={d.weather?.rainfall?.actual ?? 0}
                  plan={d.weather?.rainfall?.plan ?? 0}
                  unit="mm"
                  pct={d.weather?.rainfall?.pct ?? 0}
                  delay={160}
                />
                <KpiTile
                  Icon={Droplets}
                  label="Waste & Mud"
                  actual={d.waterfall?.wasteMud?.actual ?? 0}
                  plan={d.waterfall?.wasteMud?.plan ?? 0}
                  unit=""
                  pct={d.waterfall?.wasteMud?.pct ?? 0}
                  delay={180}
                />
              </div>

              <Section>Fuel Performance</Section>
              <div className="grid-kpi">
                <KpiTile
                  Icon={Fuel}
                  label="Fuel Issued"
                  actual={d.fuel?.issued?.actual ?? 0}
                  plan={d.fuel?.issued?.plan ?? 0}
                  unit="L"
                  pct={d.fuel?.issued?.pct ?? 0}
                  delay={140}
                />
                <KpiTile
                  Icon={Fuel}
                  label="Fuel Gain/Loss"
                  actual={d.fuel?.gainLoss?.actual ?? 0}
                  plan={d.fuel?.gainLoss?.plan ?? 0}
                  unit=""
                  pct={d.fuel?.gainLoss?.pct ?? 0}
                  delay={170}
                />
              </div>

              <Section>Join Survey</Section>
              <div
                style={{
                  background: "#fff",
                  borderRadius: 20,
                  padding: 16,
                  border: "1px solid #f1f5f9",
                  boxShadow: "0 1px 4px rgba(0,0,0,.04)",
                }}
              >
                <div className="grid-kpi">
                  {/* OB Join Survey */}
                  <div
                    style={{
                      borderRadius: 14,
                      padding: 12,
                      background: "#f8fafc",
                      border: "1px solid #f1f5f9",
                    }}
                  >
                    <div
                      style={{
                        display: "flex",
                        alignItems: "center",
                        gap: 6,
                        marginBottom: 10,
                      }}
                    >
                      <IconBox size={26}>
                        <ClipboardCheck size={12} />
                      </IconBox>
                      <div
                        style={{
                          fontSize: 10,
                          fontWeight: 800,
                          color: "#94a3b8",
                          textTransform: "uppercase",
                          letterSpacing: 1.2,
                        }}
                      >
                        OB Join Survey
                      </div>
                    </div>

                    <div
                      style={{
                        fontSize: 12,
                        color: "#64748b",
                        fontWeight: 700,
                      }}
                    >
                      JS: {d.joinSurvey?.ob?.js ?? 0} | TC:{" "}
                      {d.joinSurvey?.ob?.tc ?? 0}
                    </div>

                    <div style={{ marginTop: 10 }}>
                      <Bar value={d.joinSurvey?.ob?.pct ?? 0} />
                      <div
                        style={{
                          marginTop: 6,
                          fontSize: 12,
                          fontWeight: 800,
                          color: cTxt(d.joinSurvey?.ob?.pct ?? 0),
                        }}
                      >
                        {d.joinSurvey?.ob?.pct ?? 0}%
                      </div>
                    </div>
                  </div>

                  {/* Coal Join Survey */}
                  <div
                    style={{
                      borderRadius: 14,
                      padding: 12,
                      background: "#f8fafc",
                      border: "1px solid #f1f5f9",
                    }}
                  >
                    <div
                      style={{
                        display: "flex",
                        alignItems: "center",
                        gap: 6,
                        marginBottom: 10,
                      }}
                    >
                      <IconBox size={26}>
                        <ClipboardCheck size={12} />
                      </IconBox>
                      <div
                        style={{
                          fontSize: 10,
                          fontWeight: 800,
                          color: "#94a3b8",
                          textTransform: "uppercase",
                          letterSpacing: 1.2,
                        }}
                      >
                        Coal Join Survey
                      </div>
                    </div>

                    <div
                      style={{
                        fontSize: 12,
                        color: "#64748b",
                        fontWeight: 700,
                      }}
                    >
                      JS: {d.joinSurvey?.coal?.js ?? 0} | TC:{" "}
                      {d.joinSurvey?.coal?.tc ?? 0}
                    </div>

                    <div style={{ marginTop: 10 }}>
                      <Bar value={d.joinSurvey?.coal?.pct ?? 0} />
                      <div
                        style={{
                          marginTop: 6,
                          fontSize: 12,
                          fontWeight: 800,
                          color: cTxt(d.joinSurvey?.coal?.pct ?? 0),
                        }}
                      >
                        {d.joinSurvey?.coal?.pct ?? 0}%
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <Section>Rankings</Section>
              <RankTable
                title="Production Performance"
                Icon={BarChart3}
                ranking={RANKING}
              />
              <RankTable
                title="PA Performance"
                Icon={Hammer}
                ranking={RANKING}
              />
              <RankTable title="Productivity" Icon={Zap} ranking={RANKING} />
            </>
          )}

          {/* ═══ FINANCIAL ═══ */}
          {tab === "financial" && (
            <>
              <FinanceHero fin={FIN} />
            </>
          )}
        </div>
      </div>
    </div>
  );
}