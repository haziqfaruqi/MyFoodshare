import React, { useState } from 'react';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, LineChart, Line, PieChart, Pie, Cell } from 'recharts';
import { Download, Calendar, TrendingUp, Package, Users, Leaf, Award } from 'lucide-react';

const Reports: React.FC = () => {
  const [dateRange, setDateRange] = useState('last_month');
  const [reportType, setReportType] = useState('overview');

  const monthlyData = [
    { month: 'Jan', donations: 45, meals: 450, weight: 120, carbon: 85 },
    { month: 'Feb', donations: 52, meals: 520, weight: 140, carbon: 98 },
    { month: 'Mar', donations: 48, meals: 480, weight: 130, carbon: 91 },
    { month: 'Apr', donations: 61, meals: 610, weight: 165, carbon: 115 },
    { month: 'May', donations: 58, meals: 580, weight: 155, carbon: 108 },
    { month: 'Jun', donations: 67, meals: 670, weight: 180, carbon: 126 },
  ];

  const categoryData = [
    { name: 'Prepared Meals', value: 45, color: '#10B981', weight: 850 },
    { name: 'Bread & Bakery', value: 25, color: '#3B82F6', weight: 320 },
    { name: 'Fruits & Vegetables', value: 20, color: '#F59E0B', weight: 280 },
    { name: 'Other', value: 10, color: '#8B5CF6', weight: 150 },
  ];

  const impactMetrics = {
    totalDonations: 331,
    totalMeals: 3310,
    totalWeight: 890, // kg
    carbonSaved: 623, // kg CO2
    recipientsServed: 1245,
    wasteReduction: 89 // percentage
  };

  const topRecipients = [
    { name: 'Hope Community Center', donations: 45, meals: 450 },
    { name: 'City Homeless Shelter', donations: 38, meals: 380 },
    { name: 'Family Support Services', donations: 32, meals: 320 },
    { name: 'Senior Care Center', donations: 28, meals: 280 },
    { name: 'Youth Program Center', donations: 25, meals: 250 },
  ];

  const handleExportReport = () => {
    // Simulate report export
    console.log('Exporting report...');
  };

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8">
          <div className="flex justify-between items-center">
            <div>
              <h1 className="text-3xl font-bold text-gray-900">Impact Reports</h1>
              <p className="text-gray-600 mt-1">Track your contribution to reducing food waste and helping the community</p>
            </div>
            <button
              onClick={handleExportReport}
              className="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center"
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
                className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
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
                className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
              >
                <option value="overview">Overview</option>
                <option value="donations">Donations</option>
                <option value="impact">Environmental Impact</option>
                <option value="recipients">Recipients</option>
              </select>
            </div>

            <div className="flex items-end">
              <button className="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors flex items-center justify-center">
                <Calendar className="h-4 w-4 mr-2" />
                Generate Report
              </button>
            </div>
          </div>
        </div>

        {/* Key Metrics */}
        <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-8">
          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <Package className="h-8 w-8 text-green-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{impactMetrics.totalDonations}</p>
            <p className="text-sm text-gray-600">Total Donations</p>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <Users className="h-8 w-8 text-blue-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{impactMetrics.totalMeals.toLocaleString()}</p>
            <p className="text-sm text-gray-600">Meals Provided</p>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <TrendingUp className="h-8 w-8 text-purple-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{impactMetrics.totalWeight}</p>
            <p className="text-sm text-gray-600">Kg Food Saved</p>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <Leaf className="h-8 w-8 text-green-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{impactMetrics.carbonSaved}</p>
            <p className="text-sm text-gray-600">Kg CO₂ Saved</p>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <Users className="h-8 w-8 text-orange-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{impactMetrics.recipientsServed.toLocaleString()}</p>
            <p className="text-sm text-gray-600">People Served</p>
          </div>

          <div className="bg-white p-6 rounded-lg shadow-lg text-center">
            <Award className="h-8 w-8 text-yellow-600 mx-auto mb-2" />
            <p className="text-2xl font-bold text-gray-900">{impactMetrics.wasteReduction}%</p>
            <p className="text-sm text-gray-600">Waste Reduction</p>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
          {/* Monthly Trends */}
          <div className="bg-white rounded-lg shadow-lg p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Monthly Donation Trends</h2>
            <ResponsiveContainer width="100%" height={300}>
              <LineChart data={monthlyData}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="month" />
                <YAxis />
                <Tooltip />
                <Line type="monotone" dataKey="donations" stroke="#10B981" strokeWidth={2} name="Donations" />
                <Line type="monotone" dataKey="meals" stroke="#3B82F6" strokeWidth={2} name="Meals" />
              </LineChart>
            </ResponsiveContainer>
          </div>

          {/* Food Category Distribution */}
          <div className="bg-white rounded-lg shadow-lg p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Food Category Distribution</h2>
            <div className="flex items-center">
              <div className="w-1/2">
                <ResponsiveContainer width="100%" height={250}>
                  <PieChart>
                    <Pie
                      data={categoryData}
                      cx="50%"
                      cy="50%"
                      innerRadius={40}
                      outerRadius={80}
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
              <div className="w-1/2 space-y-3">
                {categoryData.map((category, index) => (
                  <div key={index} className="flex items-center justify-between">
                    <div className="flex items-center">
                      <div className={`w-3 h-3 rounded-full mr-2`} style={{ backgroundColor: category.color }}></div>
                      <span className="text-sm text-gray-700">{category.name}</span>
                    </div>
                    <div className="text-right">
                      <div className="text-sm font-semibold text-gray-900">{category.value}%</div>
                      <div className="text-xs text-gray-500">{category.weight}kg</div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          {/* Environmental Impact */}
          <div className="bg-white rounded-lg shadow-lg p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Environmental Impact</h2>
            <ResponsiveContainer width="100%" height={300}>
              <BarChart data={monthlyData}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="month" />
                <YAxis />
                <Tooltip />
                <Bar dataKey="weight" fill="#10B981" name="Food Saved (kg)" />
                <Bar dataKey="carbon" fill="#3B82F6" name="CO₂ Saved (kg)" />
              </BarChart>
            </ResponsiveContainer>
            <div className="mt-4 p-4 bg-green-50 rounded-lg">
              <div className="flex items-center">
                <Leaf className="h-5 w-5 text-green-600 mr-2" />
                <span className="text-sm font-medium text-green-800">
                  Your donations have saved {impactMetrics.carbonSaved}kg of CO₂ emissions!
                </span>
              </div>
              <p className="text-xs text-green-700 mt-1">
                Equivalent to taking a car off the road for 1,500 miles.
              </p>
            </div>
          </div>

          {/* Top Recipients */}
          <div className="bg-white rounded-lg shadow-lg p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">Top Recipients</h2>
            <div className="space-y-4">
              {topRecipients.map((recipient, index) => (
                <div key={index} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <div className="flex items-center">
                    <div className="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                      <span className="text-sm font-semibold text-blue-600">{index + 1}</span>
                    </div>
                    <div>
                      <p className="font-medium text-gray-900">{recipient.name}</p>
                      <p className="text-sm text-gray-600">{recipient.meals} meals provided</p>
                    </div>
                  </div>
                  <div className="text-right">
                    <p className="font-semibold text-gray-900">{recipient.donations}</p>
                    <p className="text-xs text-gray-500">donations</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Achievement Banner */}
        <div className="mt-8 bg-gradient-to-r from-green-500 to-blue-500 rounded-lg shadow-lg p-8 text-white text-center">
          <Award className="h-12 w-12 mx-auto mb-4" />
          <h2 className="text-2xl font-bold mb-2">Congratulations!</h2>
          <p className="text-green-100 mb-4">
            You've achieved a {impactMetrics.wasteReduction}% waste reduction rate this month!
          </p>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div className="bg-white bg-opacity-20 rounded-lg p-4">
              <p className="text-2xl font-bold">{impactMetrics.totalMeals.toLocaleString()}</p>
              <p className="text-sm">Meals Provided</p>
            </div>
            <div className="bg-white bg-opacity-20 rounded-lg p-4">
              <p className="text-2xl font-bold">{impactMetrics.totalWeight}kg</p>
              <p className="text-sm">Food Saved</p>
            </div>
            <div className="bg-white bg-opacity-20 rounded-lg p-4">
              <p className="text-2xl font-bold">{impactMetrics.recipientsServed}</p>
              <p className="text-sm">People Helped</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Reports;