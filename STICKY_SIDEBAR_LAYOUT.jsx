/**
 * GeloWash Customer Dashboard Layout
 * 
 * CRITICAL: This layout demonstrates the correct sticky sidebar implementation.
 * - Right sidebar uses `lg:sticky` (never fixed)
 * - Parent container has NO overflow-hidden/overflow-auto
 * - Main content uses flex-1 to allow independent scrolling
 * - Mobile: sidebar moves to bottom in normal flow
 */

export default function DashboardLayout() {
  return (
    <div className="flex flex-col lg:flex-row gap-6 p-6 min-h-screen bg-gray-50">
      {/* ============================================================
          LEFT SIDEBAR - NAVIGATION MENU
          ============================================================ */}
      <aside className="w-72 lg:w-64 flex-shrink-0">
        <nav className="bg-white rounded-xl shadow-sm p-6 space-y-4">
          <h2 className="text-lg font-bold text-gray-900 mb-6">Menu</h2>
          <NavLink href="/dashboard" icon="🏠" label="Dashboard" />
          <NavLink href="/orders" icon="📦" label="My Orders" />
          <NavLink href="/settings" icon="⚙️" label="Settings" />
          <NavLink href="/help" icon="❓" label="Help & Support" />
        </nav>
      </aside>

      {/* ============================================================
          MAIN CONTENT AREA - SCROLLABLE
          ============================================================ */}
      <main className="flex-1 space-y-8 min-w-0">
        {/* Welcome Section */}
        <section className="space-y-2">
          <h1 className="text-3xl font-bold text-gray-900">Welcome back, John!</h1>
          <p className="text-gray-600">Manage your laundry orders and track deliveries</p>
        </section>

        {/* Quick Stats Cards */}
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <StatCard
            label="Active Orders"
            value="0"
            icon="📋"
            color="blue"
          />
          <StatCard
            label="Orders Completed"
            value="12"
            icon="✓"
            color="green"
          />
          <StatCard
            label="Total Spent"
            value="₱2,450"
            icon="💳"
            color="purple"
          />
        </div>

        {/* Active Orders Section */}
        <section className="space-y-4">
          <h2 className="text-xl font-bold text-gray-900">Active Orders</h2>
          <div className="bg-white rounded-xl shadow-sm p-12 text-center">
            <p className="text-gray-500 text-lg">
              No active orders yet. Start by placing a new order at the Shop Details panel →
            </p>
          </div>
        </section>

        {/* Order History Section */}
        <section className="space-y-4">
          <h2 className="text-xl font-bold text-gray-900">Order History</h2>
          <div className="space-y-3">
            <OrderHistoryCard
              orderNo="ORD-2026-001"
              date="Feb 15, 2026"
              amount="₱450"
              status="Completed"
            />
            <OrderHistoryCard
              orderNo="ORD-2026-002"
              date="Feb 10, 2026"
              amount="₱320"
              status="Completed"
            />
            <OrderHistoryCard
              orderNo="ORD-2026-003"
              date="Feb 5, 2026"
              amount="₱1,680"
              status="Completed"
            />
          </div>
        </section>
      </main>

      {/* ============================================================
          RIGHT SIDEBAR - SHOP DETAILS (STICKY ON LG+)
          ============================================================
          
          CRITICAL CLASSES BREAKDOWN:
          - hidden lg:block          → Hide on mobile, show on lg+
          - lg:sticky                → Position sticky on lg+
          - lg:top-6                 → Offset from top (1.5rem = 24px)
          - lg:self-start            → Align to flex-start (required for sticky)
          - lg:max-h-[calc(100vh-2rem)] → ~viewport height minus spacing
          - lg:overflow-y-auto       → Scroll if content exceeds max-height
          - lg:z-10                  → Stack above main content if needed
          - w-80 flex-shrink-0       → Fixed width, don't shrink
          - space-y-6                → Gap between cards
          
          KEY POINT: The parent div (this component's root) does NOT have
          overflow properties. This allows sticky positioning to work.
      ============================================================ */}
      <aside className="hidden lg:block lg:sticky lg:top-6 lg:self-start lg:max-h-[calc(100vh-2rem)] lg:overflow-y-auto lg:z-10 w-80 flex-shrink-0 space-y-6">
        
        {/* Shop Details Card */}
        <div className="bg-white rounded-xl shadow-sm p-6 space-y-4">
          <h3 className="text-lg font-bold text-gray-900">Shop Details</h3>
          
          <div className="space-y-3 text-sm">
            <div className="flex items-start gap-3">
              <span className="text-lg">📍</span>
              <div>
                <p className="font-semibold text-gray-700">Address</p>
                <p className="text-gray-600">123 Laundry St, Manila, Metro Manila 1000</p>
              </div>
            </div>
            
            <div className="flex items-start gap-3">
              <span className="text-lg">📞</span>
              <div>
                <p className="font-semibold text-gray-700">Phone</p>
                <p className="text-gray-600">(02) 1234-5678</p>
              </div>
            </div>
            
            <div className="flex items-start gap-3">
              <span className="text-lg">🕐</span>
              <div>
                <p className="font-semibold text-gray-700">Hours</p>
                <p className="text-gray-600">Mon-Sat: 7:00 AM - 7:00 PM</p>
                <p className="text-gray-600">Sun: 8:00 AM - 5:00 PM</p>
              </div>
            </div>
          </div>
        </div>

        {/* Pay Online Card */}
        <div className="bg-white rounded-xl shadow-sm p-6 space-y-4">
          <h3 className="text-lg font-bold text-gray-900">Pay Online</h3>
          
          <div className="flex flex-col items-center gap-4">
            {/* Placeholder for QR Code */}
            <div className="bg-gray-100 rounded-lg p-4 w-full aspect-square flex items-center justify-center border-2 border-dashed border-gray-300">
              <div className="text-center">
                <p className="text-sm text-gray-500 font-medium">QR Code Placeholder</p>
                <p className="text-xs text-gray-400 mt-2">Scan to pay instantly</p>
              </div>
            </div>
            
            <button className="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors duration-200">
              Pay via GCash / PayMaya
            </button>
            
            <p className="text-xs text-gray-500 text-center">
              Secure payment powered by trusted providers
            </p>
          </div>
        </div>

        {/* Service Rates Card */}
        <div className="bg-white rounded-xl shadow-sm p-6 space-y-4">
          <h3 className="text-lg font-bold text-gray-900">Service Rates</h3>
          
          <div className="space-y-3 text-sm">
            <div className="flex justify-between">
              <span className="text-gray-700">Wash & Iron (per kg)</span>
              <span className="font-semibold text-gray-900">₱25</span>
            </div>
            <div className="border-t border-gray-200"></div>
            
            <div className="flex justify-between">
              <span className="text-gray-700">Dry Cleaning</span>
              <span className="font-semibold text-gray-900">₱50+</span>
            </div>
            <div className="border-t border-gray-200"></div>
            
            <div className="flex justify-between">
              <span className="text-gray-700">Alteration Service</span>
              <span className="font-semibold text-gray-900">₱100+</span>
            </div>
            <div className="border-t border-gray-200"></div>
            
            <div className="flex justify-between">
              <span className="text-gray-700">Express Service</span>
              <span className="font-semibold text-green-600">+30% fee</span>
            </div>
          </div>
          
          <button className="w-full bg-green-50 hover:bg-green-100 text-green-700 font-semibold py-2 rounded-lg border border-green-200 transition-colors duration-200">
            Place Order
          </button>
        </div>
      </aside>
    </div>
  );
}

/**
 * Navigation Link Component
 */
function NavLink({ href, icon, label }) {
  return (
    <a
      href={href}
      className="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 font-medium"
    >
      <span className="text-xl">{icon}</span>
      <span>{label}</span>
    </a>
  );
}

/**
 * Stat Card Component
 */
function StatCard({ label, value, icon, color }) {
  const bgColors = {
    blue: "bg-blue-50",
    green: "bg-green-50",
    purple: "bg-purple-50",
  };
  const textColors = {
    blue: "text-blue-700",
    green: "text-green-700",
    purple: "text-purple-700",
  };

  return (
    <div className={`${bgColors[color]} rounded-xl p-6 space-y-2`}>
      <div className="flex items-center justify-between">
        <p className="text-gray-600 text-sm font-medium">{label}</p>
        <span className="text-2xl">{icon}</span>
      </div>
      <p className={`text-3xl font-bold ${textColors[color]}`}>{value}</p>
    </div>
  );
}

/**
 * Order History Card Component
 */
function OrderHistoryCard({ orderNo, date, amount, status }) {
  return (
    <div className="bg-white rounded-xl shadow-sm p-4 flex items-center justify-between hover:shadow-md transition-shadow duration-200">
      <div className="space-y-1">
        <p className="font-semibold text-gray-900">{orderNo}</p>
        <p className="text-sm text-gray-500">{date}</p>
      </div>
      <div className="flex items-center gap-4">
        <div className="text-right">
          <p className="font-bold text-gray-900">{amount}</p>
          <p className="text-xs font-medium text-green-600">{status}</p>
        </div>
        <button className="text-blue-600 hover:text-blue-700 font-semibold text-sm">
          View Details →
        </button>
      </div>
    </div>
  );
}
