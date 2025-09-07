import React, { useState } from 'react';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, LineChart, Line, PieChart, Pie, Cell, AreaChart, Area } from 'recharts';
import { Download, Calendar, TrendingUp, Package, Users, Leaf, Award, MapPin, AlertTriangle } from 'lucide-react';

const AdminReports: React.FC = () => {
  const [dateRange, setDateRange] = useState('last_month');
  const [reportType, setReportType] = useState('overview');

  const platformData = [
    { month: 'Jan', restaurants: 45, donations: 234, matches: 198, pickups: 185, users: 1200 },
    { month: 'Feb', donations: 267, matches: 231, pickups: 218, users: 1350 },
    { month: 'Mar', donations: 298, matches: 256, pickups: 241, users: 1480 },
    { month: 'Apr', donations: 334, matches: 289, pickups: 272, users: 1620 },
    { month: 'May', donations: 378, matches: 325, pickups: 308, users: 1780 },
    { month: 'Jun', donations: 412, matches: 358, pickups: 341, users: 1950 },
  ];

  const regionData = [
    { name: 'Central', value: 35, color: '#10B981', donations: 145 },
    { name: 'North', value: 25, color: '#3B82F6', donations: 104 },
    { name: 'South', value: 20, color: '#F59E0B', donations: 83 },
    { name: 'East', value: 12, color: '#8B5CF6', donations: 50 },
    { name: 'West', value: 8, color: '#EF4444', donations: 33 },
  ];

  const categoryData = [
    { name: 'Prepared Meals', value: 45, color: '#10B981', weight: 2850 },
    { name: 'Bread & Bakery', value: 25, color: '#3B82F6', weight: 1320 },
    { name: 'Fruits & Vegetables', value: 20, color: '#F59E0B', weight: 980 },
    { name: 'Dairy Products', value: 6, color: '#8B5CF6', weight: 450 },
    { name: 'Other', value: 4, color: '#EF4444', weight: 200 },
  ];

  const topRestaurants = [
    { name: 'Golden Spoon', donations: 89, meals: 890, efficiency: 94 },
    { name: 'Pizza Corner', donations: 76, meals: 760, efficiency: 91 },
    { name: 'Bread Basket', donations: 68, meals: 680, efficiency: 88 },
    { name: 'Fresh Bites', donations: 54, meals: 540, efficiency: 85 },
    { name: 'Green Leaf', donations: 47, meals: 470, efficiency: 82 },
  ];

  const systemMetrics = {
    totalRestaurants: 247,
    totalDonations: 2456,
    totalMeals: 24560,
    totalWeight: 6800, // kg
    carbonSaved: 4760, // kg CO2
    recipientsServed: 8945,
    wasteReduction: 87, // percentage
    platformEfficiency: 92 // percentage
  };

  const handleExportReport = () => {
    console.log('Exporting system report...');
  };

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8">
          <div className="flex justify-between items-center">
            <div>
              <h1 className="text-3xl font-bold text-gray-900">System Analytics & Reports</h1>
              <p className="text-gray-600 mt-1">Comprehensive platform performance and impact analysis</p>
            </div>
            <button
              onClick={handleExportReport}
              className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center"
            >
              <Download className="h-4 w-4 mr-2" />
              Export Report
            </button>
          </div>
        </div>

        {/* Filters */}
        <div className="bg-white rounded-lg shadow-lg p-6 mb-8">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
              <select
                value={dateRange}
                onChange={(e) => setDateRange(e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="last_week">Last Week</option>
                <option value="last_month">Last Month</option>
                <option value="last_quarter">Last Quarter</option>
                <option value="last_year">Last Year</option>
                <option value="custom">Custom Range</option>
              </select>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
              <select
                value={reportType}
                onChange={(e) => setReportType(e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="overview">Platform Overview</option>
                <option value="performance">Performance Metrics</option>
                <option value="impact">Environmental Impact</option>
                <option value="regional">Regional Analysis</option>
                <option value="efficiency">Efficiency Report</option>
              </select>
            </div>

            <div className="flex items-end">
              <button className="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors flex items-center justify-center">
                <Calendar className="h-4 w-4 mr-2" />
                Generate Report
              </button>
            </div>
          </div>
        </div>

        {/* Key Metrics */}
        <div className="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-8 gap-6 mb-8">
          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <Users className="h-8 w-8 text-blue-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{systemMetrics.totalRestaurants}</p>
            <p className="text-sm text-gray-600">Restaurants</p>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <Package className="h-8 w-8 text-green-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{systemMetrics.totalDonations.toLocaleString()}</p>
            <p className="text-sm text-gray-600">Donations</p>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <TrendingUp className="h-8 w-8 text-purple-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{systemMetrics.totalMeals.toLocaleString()}</p>
            <p className="text-sm text-gray-600">Meals</p>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <Leaf className="h-8 w-8 text-green-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{systemMetrics.totalWeight.toLocaleString()}</p>
            <p className="text-sm text-gray-600">Kg Saved</p>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <Leaf className="h-8 w-8 text-emerald-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{systemMetrics.carbonSaved.toLocaleString()}</p>
            <p className="text-sm text-gray-600">Kg CO₂</p>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <Users className="h-8 w-8 text-orange-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{systemMetrics.recipientsServed.toLocaleString()}</p>
            <p className="text-sm text-gray-600">People Served</p>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <Award className="h-8 w-8 text-yellow-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{systemMetrics.wasteReduction}%</p>
            <p className="text-sm text-gray-600">Waste Reduced</p>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <TrendingUp className="h-8 w-8 text-indigo-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{systemMetrics.platformEfficiency}%</p>
            <p className="text-sm text-gray-600">Efficiency</p>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
          {/* Platform Growth */}
          <div className="bg-white rounded-lg shadow-lg p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Platform Growth</h2>
            <ResponsiveContainer width="100%" height={300}>
              <AreaChart data={platformData}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="month" />
                <YAxis />
                <Tooltip />
                <Area type="monotone" dataKey="donations" stackId="1" stroke="#10B981" fill="#10B981" fillOpacity={0.6} name="Donations" />
                <Area type="monotone" dataKey="matches" stackId="1" stroke="#3B82F6" fill="#3B82F6" fillOpacity={0.6} name="Matches" />
                <Area type="monotone" dataKey="pickups" stackId="1" stroke="#F59E0B" fill="#F59E0B" fillOpacity={0.6} name="Pickups" />
              </AreaChart>
            </ResponsiveContainer>
          </div>

          {/* Regional Distribution */}
          <div className="bg-white rounded-lg shadow-lg p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Regional Distribution</h2>
            <div className="flex items-center">
              <div className="w-1/2">
                <ResponsiveContainer width="100%" height={250}>
                  <PieChart>
                    <Pie
                      data={regionData}
                      cx="50%"
                      cy="50%"
                      innerRadius={40}
                      outerRadius={80}
                      paddingAngle={5}
                      dataKey="value"
                    >
                      {regionData.map((entry, index) => (
                        <Cell key={`cell-${index}`} fill={entry.color} />
                      ))}
                    </Pie>
                    <Tooltip />
                  </PieChart>
                </ResponsiveContainer>
              </div>
              <div className="w-1/2 space-y-3">
                {regionData.map((region, index) => (
                  <div key={index} className="flex items-center justify-between">
                    <div className="flex items-center">
                      <div className={`w-3 h-3 rounded-full mr-2`} style={{ backgroundColor: region.color }}></div>
                      <span className="text-sm text-gray-700">{region.name}</span>
                    </div>
                    <div className="text-right">
                      <div className="text-sm font-semibold text-gray-900">{region.value}%</div>
                      <div className="text-xs text-gray-500">{region.donations} donations</div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
          {/* Food Category Analysis */}
          <div className="bg-white rounded-lg shadow-lg p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Food Category Analysis</h2>
            <ResponsiveContainer width="100%" height={300}>
              <BarChart data={categoryData} layout="horizontal">
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis type="number" />
                <YAxis dataKey="name" type="category" width={100} />
                <Tooltip />
                <Bar dataKey="weight" fill="#10B981" name="Weight (kg)" />
              </BarChart>
            </ResponsiveContainer>
          </div>

          {/* Top Performing Restaurants */}
          <div className="bg-white rounded-lg shadow-lg p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Top Performing Restaurants</h2>
            <div className="space-y-4">
              {topRestaurants.map((restaurant, index) => (
                <div key={index} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <div className="flex items-center">
                    <div className="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                      <span className="text-sm font-semibold text-blue-600">{index + 1}</span>
                    </div>
                    <div>
                      <p className="font-medium text-gray-900">{restaurant.name}</p>
                      <p className="text-sm text-gray-600">{restaurant.meals} meals • {restaurant.efficiency}% efficiency</p>
                    </div>
                  </div>
                  <div className="text-right">
                    <p className="font-semibold text-gray-900">{restaurant.donations}</p>
                    <p className="text-xs text-gray-500">donations</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Performance Trends */}
        <div className="bg-white rounded-lg shadow-lg p-6 mb-8">
          <h2 className="text-xl font-semibold text-gray-900 mb-4">Performance Trends</h2>
          <ResponsiveContainer width="100%" height={400}>
            <LineChart data={platformData}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="month" />
              <YAxis />
              <Tooltip />
              <Line type="monotone" dataKey="donations" stroke="#10B981" strokeWidth={3} name="Donations" />
              <Line type="monotone" dataKey="matches" stroke="#3B82F6" strokeWidth={3} name="Matches" />
              <Line type="monotone" dataKey="pickups" stroke="#F59E0B" strokeWidth={3} name="Successful Pickups" />
              <Line type="monotone" dataKey="users" stroke="#8B5CF6" strokeWidth={3} name="Total Users" />
            </LineChart>
          </ResponsiveContainer>
        </div>

        {/* System Health & Alerts */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <div className="bg-white rounded-lg shadow-lg p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">System Health</h2>
            <div className="space-y-4">
              <div className="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                <div className="flex items-center">
                  <div className="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                  <span className="text-gray-700">API Response Time</span>
                </div>
                <span className="font-semibold text-green-600">245ms</span>
              </div>
              <div className="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                <div className="flex items-center">
                  <div className="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                  <span className="text-gray-700">Database Performance</span>
                </div>
                <span className="font-semibold text-green-600">Optimal</span>
              </div>
              <div className="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                <div className="flex items-center">
                  <div className="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                  <span className="text-gray-700">System Uptime</span>
                </div>
                <span className="font-semibold text-green-600">99.9%</span>
              </div>
              <div className="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                <div className="flex items-center">
                  <div className="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                  <span className="text-gray-700">Storage Usage</span>
                </div>
                <span className="font-semibold text-yellow-600">78%</span>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-lg p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Recent Alerts</h2>
            <div className="space-y-3">
              <div className="flex items-start p-3 bg-red-50 rounded-lg">
                <AlertTriangle className="h-5 w-5 text-red-500 mr-3 mt-0.5" />
                <div>
                  <p className="text-sm font-medium text-red-800">High Volume Alert</p>
                  <p className="text-xs text-red-600">Central region experiencing 40% above normal donation volume</p>
                  <p className="text-xs text-red-500 mt-1">2 hours ago</p>
                </div>
              </div>
              <div className="flex items-start p-3 bg-yellow-50 rounded-lg">
                <AlertTriangle className="h-5 w-5 text-yellow-500 mr-3 mt-0.5" />
                <div>
                  <p className="text-sm font-medium text-yellow-800">Performance Warning</p>
                  <p className="text-xs text-yellow-600">API response time increased by 15% in the last hour</p>
                  <p className="text-xs text-yellow-500 mt-1">1 hour ago</p>
                </div>
              </div>
              <div className="flex items-start p-3 bg-blue-50 rounded-lg">
                <AlertTriangle className="h-5 w-5 text-blue-500 mr-3 mt-0.5" />
                <div>
                  <p className="text-sm font-medium text-blue-800">System Update</p>
                  <p className="text-xs text-blue-600">Scheduled maintenance completed successfully</p>
                  <p className="text-xs text-blue-500 mt-1">6 hours ago</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Impact Summary */}
        <div className="mt-8 bg-gradient-to-r from-blue-500 to-green-500 rounded-lg shadow-lg p-8 text-white text-center">
          <Award className="h-12 w-12 mx-auto mb-4" />
          <h2 className="text-2xl font-bold mb-2">Platform Impact Summary</h2>
          <p className="text-blue-100 mb-4">
            MyFoodshare has successfully facilitated the redistribution of {systemMetrics.totalWeight.toLocaleString()}kg of food, 
            preventing {systemMetrics.carbonSaved.toLocaleString()}kg of CO₂ emissions and serving {systemMetrics.recipientsServed.toLocaleString()} people in need.
          </p>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div className="bg-white bg-opacity-20 rounded-lg p-4">
              <p className="text-2xl font-bold">{systemMetrics.totalMeals.toLocaleString()}</p>
              <p className="text-sm">Total Meals</p>
            </div>
            <div className="bg-white bg-opacity-20 rounded-lg p-4">
              <p className="text-2xl font-bold">{systemMetrics.totalWeight.toLocaleString()}kg</p>
              <p className="text-sm">Food Saved</p>
            </div>
            <div className="bg-white bg-opacity-20 rounded-lg p-4">
              <p className="text-2xl font-bold">{systemMetrics.carbonSaved.toLocaleString()}kg</p>
              <p className="text-sm">CO₂ Prevented</p>
            </div>
            <div className="bg-white bg-opacity-20 rounded-lg p-4">
              <p className="text-2xl font-bold">{systemMetrics.wasteReduction}%</p>
              <p className="text-sm">Waste Reduction</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AdminReports;