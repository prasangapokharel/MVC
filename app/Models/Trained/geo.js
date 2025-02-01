const geoEntities = {
    regions: [
      { name: "Terai", description: "Lowland plains in southern Nepal" },
      { name: "Hill", description: "Central hilly region of Nepal" },
      { name: "Mountain", description: "Himalayan region in northern Nepal" },
    ],
    rivers: [
      { name: "Koshi", length: 720, source: "Tibet" },
      { name: "Gandaki", length: 630, source: "Himalayas" },
      { name: "Karnali", length: 507, source: "Mount Kailash" },
    ],
    mountains: [
      { name: "Mount Everest", height: 8848, range: "Mahalangur Himal" },
      { name: "Kanchenjunga", height: 8586, range: "Kanchenjunga Himal" },
      { name: "Lhotse", height: 8516, range: "Mahalangur Himal" },
    ],
    nationalParks: [
      { name: "Chitwan National Park", area: 952, established: 1973 },
      { name: "Sagarmatha National Park", area: 1148, established: 1976 },
      { name: "Langtang National Park", area: 1710, established: 1976 },
    ],
  }
  
  const geoRelations = {
    regionContains: {
      Terai: ["Chitwan National Park"],
      Mountain: ["Mount Everest", "Kanchenjunga", "Lhotse", "Sagarmatha National Park", "Langtang National Park"],
    },
    riverFlowsThrough: {
      Koshi: ["Mountain", "Hill", "Terai"],
      Gandaki: ["Mountain", "Hill", "Terai"],
      Karnali: ["Mountain", "Hill", "Terai"],
    },
  }
  
  module.exports = { geoEntities, geoRelations }
  
  