const nepalFacts = [
    "Nepal is home to eight of the world's ten tallest mountains.",
    "The Terai region covers about 17% of Nepal's total land area.",
    "Nepal has over 6,000 rivers and rivulets.",
    "The climate varies from tropical in the south to arctic in the north.",
    "Nepal has 118 ecosystems, 75 vegetation types, and 35 forest types.",
    "The country is divided into seven provinces and 77 districts.",
    "Nepal's lowest point is Kechana Kalan at 59 meters above sea level.",
    "Rara Lake is the largest and deepest freshwater lake in Nepal.",
  ]
  
  const climaticZones = [
    { name: "Tropical", elevation: "Below 1,000 meters", characteristics: "Hot and humid" },
    { name: "Subtropical", elevation: "1,000 to 2,000 meters", characteristics: "Warm summers, mild winters" },
    { name: "Temperate", elevation: "2,000 to 3,000 meters", characteristics: "Cool summers, cold winters" },
    { name: "Subalpine", elevation: "3,000 to 4,000 meters", characteristics: "Cool summers, severe winters" },
    { name: "Alpine", elevation: "Above 4,000 meters", characteristics: "Arctic-like conditions" },
  ]
  
  const environmentalIssues = [
    {
      issue: "Deforestation",
      causes: ["Agricultural expansion", "Urbanization"],
      impacts: ["Loss of biodiversity", "Soil erosion"],
    },
    {
      issue: "Air pollution",
      causes: ["Vehicle emissions", "Industrial activities"],
      impacts: ["Health problems", "Smog in urban areas"],
    },
    {
      issue: "Water pollution",
      causes: ["Industrial waste", "Lack of proper sanitation"],
      impacts: ["Waterborne diseases", "Ecosystem damage"],
    },
    {
      issue: "Glacial lake outburst floods",
      causes: ["Climate change"],
      impacts: ["Flooding", "Damage to infrastructure"],
    },
  ]
  
  module.exports = { nepalFacts, climaticZones, environmentalIssues }
  
  