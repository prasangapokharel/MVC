const STANDARD_DISTANCE_CM = 40
const OPTOTYPE_SIZES = {
  "20/200": 87.3,
  "20/100": 43.7,
  "20/70": 30.5,
  "20/50": 21.8,
  "20/40": 17.5,
  "20/30": 13.1,
  "20/25": 10.9,
  "20/20": 8.7,
  "20/15": 6.5,
  "20/12": 5.2,
  "20/10": 4.4,
}
const SLOAN_LETTERS = ["C", "D", "H", "K", "N", "O", "R", "S", "V", "Z"]
let currentTest = {
  type: "sloan",
  level: 1,
  correctAnswers: 0,
  currentSize: null,
  currentLetter: "",
  screenDistance: STANDARD_DISTANCE_CM,
  results: [],
}
const patientInfo = {}

document.getElementById("patientForm").addEventListener("submit", (e) => {
  e.preventDefault()
  patientInfo.firstName = document.getElementById("firstName").value
  patientInfo.age = document.getElementById("age").value
  document.getElementById("patientInfo").classList.add("hidden")
  document.getElementById("calibration").classList.remove("hidden")
})

function startCalibration() {
  document.getElementById("calibration").classList.add("hidden")
  document.getElementById("setup").classList.remove("hidden")
}

function updateTestParameters() {
  currentTest.screenDistance = Number.parseFloat(document.getElementById("screenDistance").value)
}

function updateTestType() {
  currentTest.type = document.getElementById("testType").value
  setupTestDisplay()
}

function setupTestDisplay() {
  const standardInput = document.getElementById("standardInput")
  const eDirections = document.getElementById("eDirections")
  const contrastTest = document.getElementById("contrastTest")

  standardInput.classList.add("hidden")
  eDirections.classList.add("hidden")
  contrastTest.classList.add("hidden")

  switch (currentTest.type) {
    case "sloan":
      standardInput.classList.remove("hidden")
      break
    case "illiterate-e":
      eDirections.classList.remove("hidden")
      break
    case "contrast":
      contrastTest.classList.remove("hidden")
      break
  }
}

function startTest() {
  document.getElementById("setup").classList.add("hidden")
  document.getElementById("testArea").classList.remove("hidden")
  currentTest.level = 1
  currentTest.correctAnswers = 0
  currentTest.results = []
  generateNewOptotype()
}

function generateNewOptotype() {
  const sizeKeys = Object.keys(OPTOTYPE_SIZES)
  currentTest.currentSize = OPTOTYPE_SIZES[sizeKeys[currentTest.level - 1]]

  switch (currentTest.type) {
    case "sloan":
      currentTest.currentLetter = SLOAN_LETTERS[Math.floor(Math.random() * SLOAN_LETTERS.length)]
      break
    case "illiterate-e":
      const directions = ["up", "down", "left", "right"]
      currentTest.currentLetter = directions[Math.floor(Math.random() * directions.length)]
      break
    case "contrast":
      currentTest.currentLetter = SLOAN_LETTERS[Math.floor(Math.random() * SLOAN_LETTERS.length)]
      document.getElementById("contrastSlider").value = 100
      break
  }

  updateDisplay()
}

function updateDisplay() {
  const display = document.getElementById("letterDisplay")
  const sizeMM = currentTest.currentSize * (currentTest.screenDistance / STANDARD_DISTANCE_CM)
  display.style.fontSize = `${sizeMM}mm`

  if (currentTest.type === "contrast") {
    const contrast = document.getElementById("contrastSlider").value
    display.style.opacity = contrast / 100
  } else {
    display.style.opacity = 1
  }

  if (currentTest.type === "illiterate-e") {
    display.textContent = "E"
    display.style.transform = `rotate(${getRotationForDirection(currentTest.currentLetter)}deg)`
  } else {
    display.textContent = currentTest.currentLetter
    display.style.transform = "none"
  }

  document.getElementById("currentLevel").textContent = currentTest.level
}

function getRotationForDirection(direction) {
  switch (direction) {
    case "up":
      return 0
    case "right":
      return 90
    case "down":
      return 180
    case "left":
      return 270
  }
}

function checkAnswer(response) {
  let isCorrect = false
  if (currentTest.type === "sloan") {
    const userInput = document.getElementById("userInput").value.toUpperCase()
    isCorrect = userInput === currentTest.currentLetter
  } else if (currentTest.type === "illiterate-e") {
    isCorrect = response === currentTest.currentLetter
  } else if (currentTest.type === "contrast") {
    const contrast = document.getElementById("contrastSlider").value
    isCorrect = contrast > 0
  }

  currentTest.results.push({
    level: currentTest.level,
    size: currentTest.currentSize,
    correct: isCorrect,
  })

  if (isCorrect) currentTest.correctAnswers++

  currentTest.level++
  if (currentTest.level <= Object.keys(OPTOTYPE_SIZES).length) {
    generateNewOptotype()
    if (currentTest.type === "sloan") {
      document.getElementById("userInput").value = ""
    }
  } else {
    showResults()
  }
}

function submitDirection(direction) {
  checkAnswer(direction)
}

function updateContrast() {
  const contrast = document.getElementById("contrastSlider").value
  document.getElementById("contrastLetter").style.opacity = contrast / 100
}

function submitContrast() {
  checkAnswer("contrast")
}

function calculateVisualAcuity() {
  let lastCorrectLevel = 0
  for (let i = currentTest.results.length - 1; i >= 0; i--) {
    if (currentTest.results[i].correct) {
      lastCorrectLevel = i + 1
      break
    }
  }

  const sizeKeys = Object.keys(OPTOTYPE_SIZES)
  return sizeKeys[lastCorrectLevel - 1] || "20/200+"
}

function showResults() {
  document.getElementById("testArea").classList.add("hidden")
  document.getElementById("results").classList.remove("hidden")

  const acuity = calculateVisualAcuity()
  document.getElementById("acuityScore").textContent = acuity

  let recommendation = ""
  if (acuity === "20/20" || acuity === "20/15" || acuity === "20/12" || acuity === "20/10") {
    recommendation = "Your vision appears to be excellent. Regular check-ups recommended."
  } else if (acuity === "20/25" || acuity === "20/30") {
    recommendation = "Minor vision correction might be beneficial. Consider consulting an optometrist."
  } else if (acuity === "20/40" || acuity === "20/50") {
    recommendation = "Vision correction recommended. Please schedule an eye examination."
  } else {
    recommendation = "Immediate professional eye examination recommended."
  }

  document.getElementById("recommendation").textContent = recommendation

  const resultsList = document.getElementById("resultsList")
  resultsList.innerHTML = currentTest.results
    .map((result, index) => {
      const sizeKeys = Object.keys(OPTOTYPE_SIZES)
      return `<li>Level ${index + 1} (${sizeKeys[index]}): ${result.correct ? "Correct" : "Incorrect"}</li>`
    })
    .join("")
}

function resetTest() {
  document.getElementById("results").classList.add("hidden")
  document.getElementById("patientInfo").classList.remove("hidden")
  document.getElementById("firstName").value = ""
  document.getElementById("age").value = ""
  currentTest = {
    type: "sloan",
    level: 1,
    correctAnswers: 0,
    currentSize: null,
    currentLetter: "",
    screenDistance: STANDARD_DISTANCE_CM,
    results: [],
  }
  updateTestType()
}

function generateReport() {
  const { jsPDF } = window.jspdf
  const doc = new jsPDF()

  // Set document properties
  doc.setProperties({
    title: "Vision Test Report",
    subject: "Results of the online vision test",
    author: "Advanced Eye Test",
    keywords: "vision, eye test, report",
    creator: "Advanced Eye Test System",
  })

  // Add logo (assuming you have a logo.png in your images folder)
  doc.addImage("/images/logo.png", "PNG", 10, 10, 40, 40)

  // Title
  doc.setFontSize(22)
  doc.setTextColor(0)
  doc.text("Vision Test Report", 105, 40, null, null, "center")

  // Patient Information
  doc.setFontSize(14)
  doc.text("Patient Information", 20, 60)
  doc.setFontSize(12)
  doc.text(`Name: ${patientInfo.firstName}`, 20, 70)
  doc.text(`Age: ${patientInfo.age}`, 20, 80)
  doc.text(`Test Date: ${new Date().toLocaleDateString()}`, 20, 90)

  // Test Results
  doc.setFontSize(14)
  doc.text("Test Results", 20, 110)
  doc.setFontSize(12)
  doc.text(`Visual Acuity: ${document.getElementById("acuityScore").textContent}`, 20, 120)

  const recommendation = document.getElementById("recommendation").textContent
  const wrappedRecommendation = doc.splitTextToSize(`Recommendation: ${recommendation}`, 170)
  doc.text(wrappedRecommendation, 20, 130)

  // Detailed Analysis
  doc.setFontSize(14)
  doc.text("Detailed Analysis", 20, 160)
  doc.setFontSize(10)
  const resultsList = document.getElementById("resultsList").children
  let yPos = 170
  for (let i = 0; i < resultsList.length; i++) {
    doc.text(resultsList[i].textContent, 20, yPos)
    yPos += 10
    if (yPos > 280) {
      doc.addPage()
      yPos = 20
    }
  }

  // Footer
  const pageCount = doc.internal.getNumberOfPages()
  for (let i = 1; i <= pageCount; i++) {
    doc.setPage(i)
    doc.setFontSize(10)
    doc.text(`Page ${i} of ${pageCount}`, 105, 290, null, null, "center")
  }

  // Save the PDF
  doc.save("VisionTestReport.pdf")
}

