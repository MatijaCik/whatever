#include <stdio.h>
#include <stdlib.h>
#include <time.h>


void zamijeni(int* a, int* b) {
    int temp = *a;
    *a = *b;
    *b = temp;
}


void podesi(int* V, int n, int i) {
    int najveci = i;
    int lijevo = 2 * i + 1;
    int desno = 2 * i + 2;

    if (lijevo < n && V[lijevo] > V[najveci])
        najveci = lijevo;
    if (desno < n && V[desno] > V[najveci])
        najveci = desno;

    if (najveci != i) {
        zamijeni(&V[i], &V[najveci]);
        podesi(V, n, najveci);
    }
}

void heap_sort(int* V, int n) {
    for (int i = n / 2 - 1; i >= 0; i--)
        podesi(V, n, i);

    for (int i = n - 1; i > 0; i--) {
        zamijeni(&V[0], &V[i]);
        podesi(V, i, 0);
    }
}


void bubble_sort(int* V, int n) {
    int zamjena;
    for (int i = 0; i < n - 1; i++) {
        zamjena = 0;
        for (int j = 0; j < n - i - 1; j++) {
            if (V[j] > V[j + 1]) {
                zamijeni(&V[j], &V[j + 1]);
                zamjena = 1;
            }
        }
        if (!zamjena)
            break;
    }
}


void merge(int* V, int l, int m, int r) {
    int n1 = m - l + 1;
    int n2 = r - m;

    int* L = (int*)malloc(n1 * sizeof(int));
    int* R = (int*)malloc(n2 * sizeof(int));

    for (int i = 0; i < n1; i++) L[i] = V[l + i];
    for (int j = 0; j < n2; j++) R[j] = V[m + 1 + j];

    int i = 0, j = 0, k = l;
    while (i < n1 && j < n2) {
        if (L[i] <= R[j])
            V[k++] = L[i++];
        else
            V[k++] = R[j++];
    }

    while (i < n1) V[k++] = L[i++];
    while (j < n2) V[k++] = R[j++];

    free(L);
    free(R);
}

void merge_sort(int* V, int l, int r) {
    if (l < r) {
        int m = l + (r - l) / 2;
        merge_sort(V, l, m);
        merge_sort(V, m + 1, r);
        merge(V, l, m, r);
    }
}


void ispisi(int* V, int n) {
    for (int i = 0; i < n; i++)
        printf("%d ", V[i]);
    printf("\n");
}

void kopiraj(int* izvor, int* odrediste, int n) {
    for (int i = 0; i < n; i++)
        odrediste[i] = izvor[i];
}


int main() {
    srand(time(NULL));
    int N = 2500;
    int* A = (int*)malloc(N * sizeof(int));
    int* B = (int*)malloc(N * sizeof(int));

    
    for (int i = 0; i < N; i++)
        A[i] = rand() % 10000;

    kopiraj(A, B, N);
    clock_t start = clock();
    bubble_sort(B, N);
    clock_t end = clock();
    double time_bubble = (double)(end - start) / CLOCKS_PER_SEC * 1000;

   
    kopiraj(A, B, N);
    start = clock();
    heap_sort(B, N);
    end = clock();
    double time_heap = (double)(end - start) / CLOCKS_PER_SEC * 1000;

    
    kopiraj(A, B, N);
    start = clock();
    merge_sort(B, 0, N - 1);
    end = clock();
    double time_merge = (double)(end - start) / CLOCKS_PER_SEC * 1000;

   
    printf("Vrijeme Bubble sort: %.2f ms\n", time_bubble);
    printf("Vrijeme Heap sort:   %.2f ms\n", time_heap);
    printf("Vrijeme Merge sort:  %.2f ms\n", time_merge);

    free(A);
    free(B);
    return 0;
}
