const fs = require('fs');
const content = fs.readFileSync('c:/Biogenix-website/resources/views/information/diagnostic-quiz.blade.php', 'utf-8');
const regex = /<div\s+class="[^"]*quiz-step[^"]*"[^>]*data-step="([^"]+)"/g;
let match;
const steps = [];
while ((match = regex.exec(content)) !== null) {
    steps.push({ step: match[1], index: match.index });
}
console.log(steps);
