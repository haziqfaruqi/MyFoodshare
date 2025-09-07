import React from 'react';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, PieChart, Pie, Cell } from 'recharts';
import { Users, Package, TrendingUp, AlertTriangle, MapPin, Clock, CheckCircle, XCircle, Shield } from 'lucide-react';

const AdminDashboard: React.FC = () => {
  const monthlyData = [
    { month: 'Jan', listings: 45, matches: 38, pickups: 35 },
    { month: 'Feb', listings: 52, matches: 44, pickups: 41 },
    { month: 'Mar', listings: 48, matches: 41, pickups: 38 },
    { month: 'Apr', listings: 61, matches: 52, pickups: 49 },
    { month: 'May', listings: 58, matches: 49, pickups: 46 },
    { month: 'Jun', listings: 67, matches: 58, pickups: 54 },
  ];

  const categoryData = [
    { name: 'Prepared Meals', value: 45, color: '#10B981' },
    { name: 'Bread & Bakery', value: 25, color: '#3B82F6' },
    { name: 'Fruits & Vegetables', value: 20, color: '#F59E0B' },
    { name: 'Other', value: 10, color: '#8B5CF6' },
  ];

  const recentActivity = [
    { id: 1, restaurant: 'Golden Spoon', action: 'Listed 30 sandwiches', time: '5 min ago', status: 'success' },
    { id: 2, restaurant: 'Pizza Corner', action: 'Pickup completed', time: '15 min ago', status: 'success' },
    { id: 3, restaurant: 'Bread Basket', action: 'Flagged: Quality concern', time: '1 hour ago', status: 'warning' },
    { id: 4, restaurant: 'Green Leaf', action: 'New registration pending', time: '2 hours ago', status: 'pending' },
  ];

  const flaggedIssues = [
    { id: 1, restaurant: 'Fast Bites', issue: 'Quality complaint from recipient', priority: 'high', time: '2 hours ago' },
    { id: 2, restaurant: 'Corner Café', issue: 'Multiple no-shows for pickup', priority: 'medium', time: '1 day ago' },
    { id: 3, restaurant: 'Fresh Foods', issue: 'Incorrect quantity reported', priority: 'low', time: '2 days ago' },
  ];

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8">
          <div className="flex items-center mb-4">
            <Shield className="h-8 w-8 text-blue-600 mr-3" />
            <div>
              <h1 className="text-3xl font-bold text-gray-900">System Administration</h1>
              <p className="text-gray-600 mt-1">Platform Overview & Management</p>
            </div>
          </div>
        </div>

        {/* Key Metrics */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div className="bg-white p-6 rounded-lg shadow-lg border-l-4 border-green-500">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-gray-600">Total Restaurants</p>
                <p className="text-3xl font-bold text-green-600">247</p>
                <p className="text-sm text-green-600">+12 this month</p>
              </div>
              <Users className="h-12 w-12 text-green-500" />
            </div>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg border-l-4 border-blue-500">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-gray-600">Active Listings</p>
                <p className="text-3xl font-bold text-blue-600">89</p>
                <p className="text-sm text-blue-600">Real-time</p>
              </div>
              <Package className="h-12 w-12 text-blue-500" />
            </div>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg border-l-4 border-purple-500">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-gray-600">Total Matches</p>
                <p className="text-3xl font-bold text-purple-600">1,456</p>
                <p className="text-sm text-purple-600">This month: 234</p>
              </div>
              <TrendingUp className="h-12 w-12 text-purple-500" />
            </div>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg border-l-4 border-red-500">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-gray-600">Flagged Issues</p>
                <p className="text-3xl font-bold text-red-600">8</p>
                <p className="text-sm text-red-600">Require attention</p>
              </div>
              <AlertTriangle className="h-12 w-12 text-red-500" />
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Main Content */}
          <div className="lg:col-span-2 space-y-8">
            {/* Monthly Trends Chart */}
            <div className="bg-white rounded-lg shadow-lg p-6">
              <h2 className="text-xl font-semibold text-gray-900 mb-4">Monthly Trends</h2>
              <ResponsiveContainer width="100%" height={300}>
                <BarChart data={monthlyData}>
                  <CartesianGrid strokeDasharray="3 3" />
                  <XAxis dataKey="month" />
                  <YAxis />
                  <Tooltip />
                  <Bar dataKey="listings" fill="#10B981" name="Listings" />
                  <Bar dataKey="matches" fill="#3B82F6" name="Matches" />
                  <Bar dataKey="pickups" fill="#F59E0B" name="Pickups" />
                </BarChart>
              </ResponsiveContainer>
            </div>

            {/* Food Category Distribution */}
            <div className="bg-white rounded-lg shadow-lg p-6">
              <h2 className="text-xl font-semibold text-gray-900 mb-4">Food Category Distribution</h2>
              <div className="flex flex-col md:flex-row items-center">
                <div className="w-full md:w-1/2">
                  <ResponsiveContainer width="100%" height={250}>
                    <PieChart>
                      <Pie
                        data={categoryData}
                        cx="50%"
                        cy="50%"
                        innerRadius={60}
                        outerRadius={100}
                        paddingAngle={5}
                        dataKey="value"
                      >
                        {categoryData.map((entry, index) => (
                          <Cell key={`cell-${index}`} fill={entry.color} />
                        ))}
                      </Pie>
                      <Tooltip />
                    </PieChart>
                  </ResponsiveContainer>
                </div>
                <div className="w-full md:w-1/2 space-y-3">
                  {categoryData.map((category, index) => (
                    <div key={index} className="flex items-center justify-between">
                      <div className="flex items-center">
                        <div className={`w-4 h-4 rounded-full mr-3`} style={{ backgroundColor: category.color }}></div>
                        <span className="text-gray-700">{category.name}</span>
                      </div>
                      <span className="font-semibold text-gray-900">{category.value}%</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Recent Activity */}
            <div className="bg-white rounded-lg shadow-lg p-6">
              <h2 className="text-xl font-semibold text-gray-900 mb-4">Recent Activity</h2>
              <div className="space-y-4">
                {recentActivity.map((activity) => (
                  <div key={activity.id} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div className="flex items-center">
                      <div className={`w-2 h-2 rounded-full mr-3 ${
                        activity.status === 'success' ? 'bg-green-500' :
                        activity.status === 'warning' ? 'bg-yellow-500' :
                        'bg-blue-500'
                      }`}></div>
                      <div>
                        <p className="font-medium text-gray-900">{activity.restaurant}</p>
                        <p className="text-sm text-gray-600">{activity.action}</p>
                      </div>
                    </div>
                    <div className="text-sm text-gray-500">{activity.time}</div>
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Sidebar */}
          <div className="space-y-6">
            {/* Flagged Issues */}
            <div className="bg-white rounded-lg shadow-lg p-6">
              <div className="flex items-center justify-between mb-4">
                <h2 className="text-lg font-semibold text-gray-900">Flagged Issues</h2>
                <AlertTriangle className="h-5 w-5 text-red-500" />
              </div>
              <div className="space-y-3">
                {flaggedIssues.map((issue) => (
                  <div key={issue.id} className="border-l-4 border-red-500 pl-3 py-2">
                    <div className="flex items-center justify-between mb-1">
                      <p className="font-medium text-sm text-gray-900">{issue.restaurant}</p>
                      <span className={`text-xs px-2 py-1 rounded-full ${
                        issue.priority === 'high' ? 'bg-red-100 text-red-800' :
                        issue.priority === 'medium' ? 'bg-yellow-100 text-yellow-800' :
                        'bg-blue-100 text-blue-800'
                      }`}>
                        {issue.priority}
                      </span>
                    </div>
                    <p className="text-sm text-gray-600">{issue.issue}</p>
                    <p className="text-xs text-gray-500 mt-1">{issue.time}</p>
                  </div>
                ))}
              </div>
              <button className="w-full mt-4 bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors text-sm font-medium">
                View All Issues
              </button>
            </div>

            {/* Quick Actions */}
            <div className="bg-white rounded-lg shadow-lg p-6">
              <h2 className="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
              <div className="space-y-3">
                <button className="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors text-sm font-medium">
                  Generate Report
                </button>
                <button className="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">
                  Manage Users
                </button>
                <button className="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 transition-colors text-sm font-medium">
                  View Analytics
                </button>
                <button className="w-full bg-orange-600 text-white py-2 px-4 rounded-md hover:bg-orange-700 transition-colors text-sm font-medium">
                  System Settings
                </button>
              </div>
            </div>

            {/* Platform Health */}
            <div className="bg-white rounded-lg shadow-lg p-6">
              <h2 className="text-lg font-semibold text-gray-900 mb-4">Platform Health</h2>
              <div className="space-y-4">
                <div className="flex items-center justify-between">
                  <div className="flex items-center">
                    <CheckCircle className="h-4 w-4 text-green-500 mr-2" />
                    <span className="text-sm text-gray-600">API Status</span>
                  </div>
                  <span className="text-green-600 font-medium text-sm">Online</span>
                </div>
                <div className="flex items-center justify-between">
                  <div className="flex items-center">
                    <CheckCircle className="h-4 w-4 text-green-500 mr-2" />
                    <span className="text-sm text-gray-600">Database</span>
                  </div>
                  <span className="text-green-600 font-medium text-sm">Healthy</span>
                </div>
                <div className="flex items-center justify-between">
                  <div className="flex items-center">
                    <Clock className="h-4 w-4 text-yellow-500 mr-2" />
                    <span className="text-sm text-gray-600">Avg Response</span>
                  </div>
                  <span className="text-yellow-600 font-medium text-sm">245ms</span>
                </div>
                <div className="flex items-center justify-between">
                  <div className="flex items-center">
                    <CheckCircle className="h-4 w-4 text-green-500 mr-2" />
                    <span className="text-sm text-gray-600">Uptime</span>
                  </div>
                  <span className="text-green-600 font-medium text-sm">99.9%</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AdminDashboard;