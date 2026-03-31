Your task is to optimize my code to improve performance, especially LCP and FCP.

### Objectives:

* Eliminate or reduce render-blocking CSS
* Improve initial page load speed
* Keep the design identical (no visual regression)

### Requirements:

1. Inline the critical CSS directly into the HTML (<style> in <head>)
2. Defer non-critical CSS using techniques like:

   * preload + onload
   * or media="print" hack
3. Minimize the number of CSS files (merge if necessary)
4. Keep the code clean and production-ready
5. Do NOT break responsiveness or layout

### Output format:

* Show the optimized HTML
* Show the optimized CSS
* Clearly separate:

  * Critical CSS (inlined)
  * Deferred CSS
* Add comments explaining what you changed

### Bonus:

* Suggest additional performance improvements (optional)
* Keep everything simple and efficient

